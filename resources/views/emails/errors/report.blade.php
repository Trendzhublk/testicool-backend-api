@php
    $req = $context['request'] ?? [];
    $session = $context['session'] ?? [];
@endphp

@component('mail::message')
# Error Report

**Exception:** {{ get_class($exception) }}  
**Message:** {{ $exception->getMessage() }}  
**File:** {{ $exception->getFile() }}:{{ $exception->getLine() }}

@component('mail::panel')
```
{{ $exception->getTraceAsString() }}
```
@endcomponent

## Request
- Method: {{ $req['method'] ?? 'n/a' }}
- URL: {{ $req['url'] ?? 'n/a' }}
- IP: {{ $req['ip'] ?? 'n/a' }}
- User: {{ $req['user'] ?? 'guest' }}

**Headers**
```
{{ json_encode($req['headers'] ?? [], JSON_PRETTY_PRINT) }}
```

**Body**
```
{{ json_encode($req['body'] ?? [], JSON_PRETTY_PRINT) }}
```

**Session**
```
{{ json_encode($session, JSON_PRETTY_PRINT) }}
```

@endcomponent
