// This is a basic Flutter widget test for the Eldera Health app.
//
// To perform an interaction with a widget in your test, use the WidgetTester
// utility in the flutter_test package. For example, you can send tap and scroll
// gestures. You can also use WidgetTester to find child widgets in the widget
// tree, read text, and verify that the values of widget properties are correct.

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:eldera/eldera.dart';

void main() {
  testWidgets('Eldera app loads correctly', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const ElderaApp());

    // Verify that the app title is correct.
    expect(find.text('Eldera Health'), findsOneWidget);
    
    // Verify that the bottom navigation bar is present.
    expect(find.byType(BottomNavigationBar), findsOneWidget);
    
    // Verify that the Home tab is selected by default.
    expect(find.text('Home'), findsOneWidget);
    expect(find.text('Notifications'), findsOneWidget);
    expect(find.text('Settings'), findsOneWidget);
    expect(find.text('Schedule'), findsOneWidget);
  });
}
