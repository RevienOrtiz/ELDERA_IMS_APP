# OCR Implementation for ELDERA IMS

This document provides information about the OCR (Optical Character Recognition) technologies used in the ELDERA IMS application.

## OCR Technologies

The application uses two OCR technologies:

1. **Google Vision API v1.4**
   - Used for high-accuracy text detection and recognition
   - Provides advanced features like document text detection and handwriting recognition

2. **Tesseract OCR**
   - Open-source OCR engine
   - Used as a fallback option and for offline processing

## Installation

### Google Vision API

To install Google Vision API client library, run:

```bash
composer require google/cloud-vision
```

### Tesseract OCR

1. Install Tesseract OCR engine:
   - Download the installer from: [https://github.com/UB-Mannheim/tesseract/wiki](https://github.com/UB-Mannheim/tesseract/wiki)
   - Follow the installation instructions for your operating system

2. Install the PHP wrapper for Tesseract:

```bash
composer require thiagoalessio/tesseract_ocr
```

## Configuration

After installation, make sure to:

1. Set up your Google Cloud credentials
2. Configure the path to Tesseract executable in your environment
3. Update your `.env` file with the necessary configuration variables

## Usage

The OCR functionality is implemented in the OCRController and GoogleVisionController. These controllers provide endpoints for:

- Document scanning
- Text extraction
- Form field population based on extracted text

Refer to the API documentation for detailed information on available endpoints and their usage.