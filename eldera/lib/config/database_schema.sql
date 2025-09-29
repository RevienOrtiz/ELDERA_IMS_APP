-- ELDERA HEALTH APP - SUPABASE DATABASE SCHEMA
-- This file contains the complete database schema for the Eldera Health application
-- Execute these SQL commands in your Supabase SQL Editor

-- =============================================
-- 1. USERS TABLE (Authentication & Profiles)
-- =============================================

-- Enable Row Level Security
CREATE TABLE IF NOT EXISTS public.users (
    id UUID REFERENCES auth.users(id) PRIMARY KEY,
    name VARCHAR(100) NOT NULL CHECK (length(name) >= 2),
    age INTEGER NOT NULL CHECK (age >= 18 AND age <= 120),
    phone_number VARCHAR(20) NOT NULL CHECK (phone_number ~ '^\+63[0-9]{10}$'),
    profile_image_url TEXT, -- Will store Supabase Storage URLs instead of base64
    id_status VARCHAR(20) NOT NULL DEFAULT 'Pending' CHECK (id_status IN ('Senior Citizen', 'PWD', 'Regular', 'Pending')),
    is_dswd_pension_beneficiary BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create updated_at trigger
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON public.users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Row Level Security Policies
ALTER TABLE public.users ENABLE ROW LEVEL SECURITY;

-- Users can read their own profile
CREATE POLICY "Users can view own profile" ON public.users
    FOR SELECT USING (auth.uid() = id);

-- Users can update their own profile
CREATE POLICY "Users can update own profile" ON public.users
    FOR UPDATE USING (auth.uid() = id);

-- Users can insert their own profile (on signup)
CREATE POLICY "Users can insert own profile" ON public.users
    FOR INSERT WITH CHECK (auth.uid() = id);

-- =============================================
-- 2. ANNOUNCEMENTS TABLE
-- =============================================

CREATE TABLE IF NOT EXISTS public.announcements (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    posted_date DATE NOT NULL DEFAULT CURRENT_DATE,
    what TEXT NOT NULL,
    when_event VARCHAR(200) NOT NULL, -- 'when' is reserved keyword
    where_location VARCHAR(200) NOT NULL DEFAULT 'Lingayen, Pangasinan',
    category VARCHAR(50) NOT NULL CHECK (category IN ('Health', 'Social', 'Emergency', 'General', 'Education')),
    department VARCHAR(100) NOT NULL,
    icon_type VARCHAR(50) NOT NULL DEFAULT 'health',
    priority INTEGER NOT NULL DEFAULT 1 CHECK (priority >= 1 AND priority <= 5),
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create updated_at trigger for announcements
CREATE TRIGGER update_announcements_updated_at BEFORE UPDATE ON public.announcements
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Row Level Security for announcements
ALTER TABLE public.announcements ENABLE ROW LEVEL SECURITY;

-- All authenticated users can read active announcements
CREATE POLICY "Authenticated users can view active announcements" ON public.announcements
    FOR SELECT USING (auth.role() = 'authenticated' AND is_active = true);

-- Only admins can insert/update/delete announcements (implement admin role later)
CREATE POLICY "Admins can manage announcements" ON public.announcements
    FOR ALL USING (auth.jwt() ->> 'role' = 'admin');

-- =============================================
-- 3. USER REMINDERS TABLE
-- =============================================

CREATE TABLE IF NOT EXISTS public.user_reminders (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES public.users(id) ON DELETE CASCADE,
    announcement_id UUID REFERENCES public.announcements(id) ON DELETE CASCADE,
    reminder_type VARCHAR(50) NOT NULL CHECK (reminder_type IN ('medication', 'appointment', 'exercise', 'general')),
    reminder_time TIME NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create updated_at trigger for reminders
CREATE TRIGGER update_user_reminders_updated_at BEFORE UPDATE ON public.user_reminders
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Row Level Security for reminders
ALTER TABLE public.user_reminders ENABLE ROW LEVEL SECURITY;

-- Users can only access their own reminders
CREATE POLICY "Users can manage own reminders" ON public.user_reminders
    FOR ALL USING (auth.uid() = user_id);

-- =============================================
-- 4. NOTIFICATION LOGS TABLE
-- =============================================

CREATE TABLE IF NOT EXISTS public.notification_logs (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES public.users(id) ON DELETE CASCADE,
    announcement_id UUID REFERENCES public.announcements(id) ON DELETE SET NULL,
    notification_type VARCHAR(50) NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    sent_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    read_at TIMESTAMP WITH TIME ZONE,
    is_read BOOLEAN NOT NULL DEFAULT false
);

-- Row Level Security for notification logs
ALTER TABLE public.notification_logs ENABLE ROW LEVEL SECURITY;

-- Users can only access their own notification logs
CREATE POLICY "Users can view own notifications" ON public.notification_logs
    FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can update own notifications" ON public.notification_logs
    FOR UPDATE USING (auth.uid() = user_id);

-- =============================================
-- 5. STORAGE BUCKETS (for profile images)
-- =============================================

-- Create storage bucket for profile images
INSERT INTO storage.buckets (id, name, public)
VALUES ('profile-images', 'profile-images', true)
ON CONFLICT (id) DO NOTHING;

-- Storage policies
CREATE POLICY "Users can upload own profile image" ON storage.objects
    FOR INSERT WITH CHECK (
        bucket_id = 'profile-images' AND 
        auth.uid()::text = (storage.foldername(name))[1]
    );

CREATE POLICY "Users can view own profile image" ON storage.objects
    FOR SELECT USING (
        bucket_id = 'profile-images' AND 
        auth.uid()::text = (storage.foldername(name))[1]
    );

CREATE POLICY "Users can update own profile image" ON storage.objects
    FOR UPDATE USING (
        bucket_id = 'profile-images' AND 
        auth.uid()::text = (storage.foldername(name))[1]
    );

CREATE POLICY "Users can delete own profile image" ON storage.objects
    FOR DELETE USING (
        bucket_id = 'profile-images' AND 
        auth.uid()::text = (storage.foldername(name))[1]
    );

-- =============================================
-- 6. PRODUCTION READY - NO SAMPLE DATA
-- =============================================

-- Sample data removed for production deployment
-- Announcements will be populated by IMS integration

-- =============================================
-- 7. REAL-TIME SUBSCRIPTIONS SETUP
-- =============================================

-- Enable real-time for specific tables (IMS synchronization)
ALTER PUBLICATION supabase_realtime ADD TABLE public.announcements;
ALTER PUBLICATION supabase_realtime ADD TABLE public.user_reminders;
ALTER PUBLICATION supabase_realtime ADD TABLE public.notification_logs;
ALTER PUBLICATION supabase_realtime ADD TABLE public.users;

-- Additional real-time configuration for comprehensive IMS sync
-- This ensures all status changes from IMS are reflected in real-time
COMMENT ON TABLE public.users IS 'Real-time enabled for IMS user profile sync including DSWD benefits';
COMMENT ON TABLE public.user_reminders IS 'Real-time enabled for IMS reminder status sync';
COMMENT ON TABLE public.notification_logs IS 'Real-time enabled for IMS notification status sync';
COMMENT ON TABLE public.announcements IS 'Real-time enabled for IMS announcement sync';

-- =============================================
-- 8. FUNCTIONS FOR COMMON OPERATIONS
-- =============================================

-- Function to get user's active reminders
CREATE OR REPLACE FUNCTION get_user_active_reminders(user_uuid UUID)
RETURNS TABLE (
    reminder_id UUID,
    announcement_title VARCHAR,
    reminder_type VARCHAR,
    reminder_time TIME,
    announcement_category VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        ur.id,
        a.title,
        ur.reminder_type,
        ur.reminder_time,
        a.category
    FROM public.user_reminders ur
    JOIN public.announcements a ON ur.announcement_id = a.id
    WHERE ur.user_id = user_uuid 
    AND ur.is_active = true 
    AND a.is_active = true
    ORDER BY ur.reminder_time;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Function to mark notification as read
CREATE OR REPLACE FUNCTION mark_notification_read(notification_uuid UUID, user_uuid UUID)
RETURNS BOOLEAN AS $$
BEGIN
    UPDATE public.notification_logs 
    SET is_read = true, read_at = NOW()
    WHERE id = notification_uuid AND user_id = user_uuid;
    
    RETURN FOUND;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- =============================================
-- SETUP COMPLETE!
-- =============================================
-- 
-- Next steps:
-- 1. Copy your Supabase project URL and anon key to supabase_config.dart
-- 2. Run these SQL commands in your Supabase SQL Editor
-- 3. Test the connection by running the Flutter app
-- 4. Implement the service layer migrations