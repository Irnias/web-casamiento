<?php

namespace App\Data;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;

class LoginData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $code
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            code: strtoupper($request->input('code'))
        );
    }
}
