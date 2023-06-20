const mix = require("laravel-mix");
const path = require("path");
const fs = require("fs");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const sourcePath = "resources/js/";
const destinationPath = "public/js/build/";

const directoryPath = path.join(__dirname, "resources/js");
fs.readdir(directoryPath, (err, files) => {
    // Handle error
    if (err) return console.log("Unable to scan directory: " + err);

    // Get names of files to build
    files.forEach((file) => {
        mix.babel(`${sourcePath}${file}`, `${destinationPath}${file}`);
    });

    if (mix.inProduction()) {
        mix.version();
    }
});
