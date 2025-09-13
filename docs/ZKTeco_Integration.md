# ZKTeco Attendance Integration

This document outlines the ZKTeco biometric device integration for the HRMS system.

## Overview

The HRMS system provides secure API endpoints for ZKTeco attendance devices to push attendance data in real-time. The integration uses HMAC-SHA256 signatures for security and supports batch processing of attendance records.

## API Endpoints

### 1. Push Attendance Data (POST /api/attendance/push)

Main endpoint for ZKTeco devices to send attendance records.

**Authentication**: HMAC-SHA256 signature required
**Rate Limit**: 1000 records per request

**Request Headers:**
```
Content-Type: application/json
X-Signature: <hmac_sha256_signature>
```

**Request Body:**
```json
{
    "device_id": "ZK001",
    "records": [
        {
            "employee_id": "EMP001",
            "timestamp": "2024-01-15 08:30:00",
            "type": "check_in",
            "device_info": "ZKTeco F18"
        },
        {
            "employee_id": "EMP001",
            "timestamp": "2024-01-15 17:30:00",
            "type": "check_out",
            "device_info": "ZKTeco F18"
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Attendance data processed",
    "statistics": {
        "total_records": 2,
        "processed_successfully": 2,
        "errors": 0
    },
    "errors": null,
    "timestamp": "2024-01-15T10:30:00.000Z"
}
```

### 2. Health Check (GET /api/attendance/health)

Check if the attendance API is operational.

**Authentication**: None required

**Response:**
```json
{
    "status": "healthy",
    "service": "HRMS Attendance API",
    "version": "1.0.0",
    "timestamp": "2024-01-15T10:30:00.000Z",
    "endpoints": {
        "push_attendance": "https://hrms.sarieldin.com/api/attendance/push",
        "get_stats": "https://hrms.sarieldin.com/api/attendance/stats",
        "health_check": "https://hrms.sarieldin.com/api/attendance/health"
    }
}
```

### 3. Get Attendance Statistics (GET /api/attendance/stats)

Retrieve daily attendance statistics.

**Authentication**: API Key required
**Headers:**
```
X-API-Key: <your_api_key>
```

**Query Parameters:**
- `date` (optional): Date in Y-m-d format, defaults to today

**Response:**
```json
{
    "success": true,
    "data": {
        "date": "2024-01-15",
        "total_employees": 45,
        "checked_in": 42,
        "checked_out": 38,
        "on_break": 4,
        "absent": 3,
        "late_arrivals": 5,
        "early_departures": 2,
        "timestamp": "2024-01-15T10:30:00.000Z"
    }
}
```

### 4. Get Employee List (GET /api/attendance/employees)

Retrieve active employee list for device configuration.

**Authentication**: API Key required
**Headers:**
```
X-API-Key: <your_api_key>
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "employee_id": "EMP001",
            "name": "Ahmed Mohamed",
            "department": "Legal Affairs",
            "status": "active"
        }
    ],
    "count": 45,
    "timestamp": "2024-01-15T10:30:00.000Z"
}
```

## Security

### HMAC Signature Authentication

All attendance data pushes must include a valid HMAC-SHA256 signature.

**Signature Generation:**
1. Get the raw JSON payload
2. Generate HMAC-SHA256 using the shared secret
3. Include signature in `X-Signature` header

**Example (PHP):**
```php
$payload = json_encode($data);
$secret = 'your-secure-hmac-secret-key';
$signature = hash_hmac('sha256', $payload, $secret);
```

**Example (Python):**
```python
import hmac
import hashlib
import json

payload = json.dumps(data)
secret = b'your-secure-hmac-secret-key'
signature = hmac.new(secret, payload.encode(), hashlib.sha256).hexdigest()
```

### API Key Authentication

Statistics and employee list endpoints require API key authentication via `X-API-Key` header.

## Environment Configuration

Add these variables to your `.env` file:

```env
# ZKTeco Integration
ZKTECO_ENABLED=true
ZKTECO_API_KEY=sep-hrms-api-key-2024
ZKTECO_HMAC_SECRET=sep-hrms-secure-hmac-secret-key-2024
```

## Attendance Types

The system supports the following attendance types:

- `check_in`: Employee arrival/start of shift
- `check_out`: Employee departure/end of shift
- `break_start`: Start of break period
- `break_end`: End of break period

## Error Handling

### Common Error Responses

**Invalid Signature (401):**
```json
{
    "success": false,
    "message": "Invalid authentication signature",
    "error_code": "INVALID_SIGNATURE"
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "records.0.employee_id": ["The employee id field is required."]
    },
    "error_code": "VALIDATION_ERROR"
}
```

**Employee Not Found:**
```json
{
    "success": true,
    "message": "Attendance data processed",
    "statistics": {
        "total_records": 1,
        "processed_successfully": 0,
        "errors": 1
    },
    "errors": [
        {
            "record_index": 0,
            "employee_id": "INVALID001",
            "error": "Employee with ID INVALID001 not found or inactive"
        }
    ]
}
```

## ZKTeco Device Configuration

### 1. Device Setup

Configure your ZKTeco device with:
- **Server URL**: `https://hrms.sarieldin.com/api/attendance/push`
- **Method**: POST
- **Content-Type**: application/json
- **Authentication**: HMAC-SHA256

### 2. Employee Mapping

Ensure employee IDs in the ZKTeco device match the `code` field in the HRMS employee records.

### 3. Data Format

Configure the device to send data in the specified JSON format with proper field mapping.

## Monitoring and Logging

### Activity Logging

All attendance records are logged in the Laravel activity log with:
- Employee information
- Device ID and info
- Timestamp and attendance type
- Processing status

### System Logs

Failed authentication attempts and processing errors are logged to:
- `storage/logs/laravel.log`
- Daily log rotation enabled

### Health Monitoring

Use the health check endpoint for monitoring:
```bash
curl https://hrms.sarieldin.com/api/attendance/health
```

## Testing

### Test Attendance Push

```bash
# Generate test data
curl -X POST https://hrms.sarieldin.com/api/attendance/push \
  -H "Content-Type: application/json" \
  -H "X-Signature: <calculated_signature>" \
  -d '{
    "device_id": "TEST001",
    "records": [{
      "employee_id": "EMP001",
      "timestamp": "2024-01-15 08:30:00",
      "type": "check_in",
      "device_info": "Test Device"
    }]
  }'
```

### Validate API Endpoints

```bash
# Health check
curl https://hrms.sarieldin.com/api/attendance/health

# Get employee list
curl -H "X-API-Key: your-api-key" \
     https://hrms.sarieldin.com/api/attendance/employees

# Get attendance stats
curl -H "X-API-Key: your-api-key" \
     https://hrms.sarieldin.com/api/attendance/stats?date=2024-01-15
```

## Future Enhancements

Planned features for the attendance system:

1. **Attendance Table**: Dedicated database table for attendance records
2. **Shift Management**: Employee shift configuration and validation
3. **Overtime Calculation**: Automatic overtime detection and calculation
4. **Leave Integration**: Integration with leave management system
5. **Real-time Dashboard**: Live attendance monitoring dashboard
6. **Mobile App**: Employee self-service mobile application
7. **Biometric Enrollment**: Direct biometric enrollment through HRMS
8. **Multi-location Support**: Support for multiple office locations

## Support

For technical support or integration assistance:

- **Email**: it@sarieldin.com
- **Documentation**: `/docs/ZKTeco_Integration.md`
- **API Status**: `https://hrms.sarieldin.com/api/attendance/health`
- **Logs**: Check Laravel logs for detailed error information

---

*This integration is part of the Sarie Eldin & Partners HRMS system. For internal use only.*