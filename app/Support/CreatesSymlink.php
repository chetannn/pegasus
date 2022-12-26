<?php

namespace App\Support;

trait CreatesSymlink
{
    public function makeSymbolicLink(string $sourcePath, string $destinationPath): string
    {
        return "ln -s -f $sourcePath $destinationPath";
    }
}
