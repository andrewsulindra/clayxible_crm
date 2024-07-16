<?php
use Intervention\Image\Facades\Image;


if (!function_exists('projectStatus')) {
    /**
     * Get the project status string based on the status code.
     *
     * @param int $status
     * @return string
     */
    function projectStatusName($status)
    {
        switch ($status) {
            case config('constants.PROJECT_STATUS_OPEN'):
                return 'Open';
            case config('constants.PROJECT_STATUS_CONFIRM'):
                return 'Confirm';
            case config('constants.PROJECT_STATUS_FOLLOW_UP'):
                return 'Follow Up';
            case config('constants.PROJECT_STATUS_NEED_FOLLOW_UP'):
                return 'Need Follow Up';
            case config('constants.PROJECT_STATUS_CUT'):
                return 'Cut';
            case config('constants.PROJECT_STATUS_CLOSED'):
                return 'Closed';
            default:
                return '-';
        }
    }
}

if (!function_exists('generateUserImage')) {
    function generateUserImage($name)
    {
        // Get the first character of the name (assuming it's a single word)
        $initial = strtoupper(substr($name, 0, 1));

        // Set the size of the image and font
        $width = 128;
        $height = 128;
        $fontSize = 64;

        // Generate a random color for the background
        $bgColor = generateRandomColor();

        // Create a new image instance
        $img = Image::canvas($width, $height, $bgColor);

        // Add text (initial) to the image
        $img->text($initial, $width / 2, $height / 2, function($font) use ($fontSize) {
            $font->file(public_path('fonts/Arial.ttf')); // Adjust the font file path as needed
            // $font->file(3);
            $font->size($fontSize);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Encode the image as a base64 string
        $imageData = $img->encode('data-url');

        // Return the base64 encoded image data
        return $imageData;
    }
}

if (!function_exists('generateRandomColor')) {
    function generateRandomColor()
    {
        $minBrightness = 50; // Minimum brightness value (0 to 255)
        $maxBrightness = 150; // Maximum brightness value (0 to 255)

        // Generate random RGB values within a dark color range
        $red = mt_rand($minBrightness, $maxBrightness);
        $green = mt_rand($minBrightness, $maxBrightness);
        $blue = mt_rand($minBrightness, $maxBrightness);

        // Return the color in hexadecimal format
        return sprintf('#%02x%02x%02x', $red, $green, $blue);
    }

}