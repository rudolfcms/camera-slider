var _  = require("lodash");
var cssmin = require('gulp-cssmin');
var del = require("del");
var gulp = require("gulp");
var imagemin = require('gulp-imagemin');
var rename = require('gulp-rename');
var runSequence = require("run-sequence");
var uglify = require('gulp-uglify');

var paths = {
  bower: "./bower_components/",
  dest: "./build/"
}; 

gulp.task("css", function() {
  gulp.src(paths.bower + "Camera/css/camera.css")
    .pipe(cssmin())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(paths.dest + "css/"));
});

gulp.task('images', function() {
  gulp.src(paths.bower + "Camera/images/*.{gif,png}")
    .pipe(imagemin())
    .pipe(gulp.dest(paths.dest + "images/"));


  gulp.src(paths.bower + "Camera/images/patterns/*.png")
    .pipe(imagemin())
    .pipe(gulp.dest(paths.dest + "images/patterns/"));
});

gulp.task('scripts', function() {
  gulp.src([paths.bower + "Camera/scripts/*.js", "!"+paths.bower + "Camera/scripts/camera.min.js"])
    .pipe(uglify())
    .pipe(gulp.dest(paths.dest + "scripts/"));
});

gulp.task("php", function () {
  gulp.src("./src/**/*.php")
    .pipe(gulp.dest(paths.dest));
});

gulp.task("clean", function() {
  del.sync(paths.dest);
});

// Build Sequences
// ---------------

gulp.task("default", ["build"]);

gulp.task("build", function() {
  runSequence("clean", ["css", "images", "scripts", "php"]);
});
