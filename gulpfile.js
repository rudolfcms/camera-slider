const cssmin = require('gulp-cssmin');
const del = require("del");
const gulp = require("gulp");
const imagemin = require('gulp-imagemin');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');

const paths = {
  npm: "./node_modules/",
  dest: "./build/"
};

gulp.task("css", function(done) {
  gulp.src(paths.npm + "Camera/css/camera.css")
    .pipe(cssmin())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(paths.dest + "css/"));

  done();
});

gulp.task('images', function(done) {
  gulp.src(paths.npm + "Camera/images/*.{gif,png}")
    .pipe(imagemin())
    .pipe(gulp.dest(paths.dest + "images/"));


  gulp.src(paths.npm + "Camera/images/patterns/*.png")
    .pipe(imagemin())
    .pipe(gulp.dest(paths.dest + "images/patterns/"));

  done();
});

gulp.task('scripts', function(done) {
  gulp.src([paths.npm + "Camera/scripts/*.js", "!"+paths.npm + "Camera/scripts/camera.min.js"])
    .pipe(uglify())
    .pipe(gulp.dest(paths.dest + "scripts/"));
  done();
});

gulp.task("php", function (done) {
  gulp.src("./src/**/*.php")
    .pipe(gulp.dest(paths.dest));
    done();
});

gulp.task("clean", function() {
  return del(paths.dest);
});

// Build Sequences
// ---------------

gulp.task("build", gulp.series("clean", gulp.parallel("css", "images", "scripts", "php")));

exports.default = gulp.series("build");
