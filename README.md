# FixUTF8
Fix garbled/multiple utf8 encodings applied to a string - test push

Description
------------

If you apply PHP utf8_encode() multiple times to a string it will result in a garbled string.

Often times there might be cases when first portion of the string is encoded twice and other parts are encoded multiple times.

```bash
$garbledString = utf8_encode('Für ') . utf8_encode(utf8_encode('Straße'));
$garbledString = utf8_encode($garbledString);
```

Requirements
------------

* PHP >= 5.3.3
    
Usage
-----

```bash
<?php
use FixUTF8\Encoding;

Encoding::fixUtf8($garbledString);
```

Install via composer
--------------------
Edit your composer.json file to include the following for the latest version:

```json
{
    "require": {
        "p4bgroup/fix-utf8": "dev-master"
    }
}
```

License
-------

Copyright (c) 2016, P4B
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
