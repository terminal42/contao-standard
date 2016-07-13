'use strict';

const browserify    = require('browserify');
const gulp          = require('gulp');
const source        = require('vinyl-source-stream');
const buffer        = require('vinyl-buffer');
const gutil         = require('gulp-util');
const uglify        = require('gulp-uglify');
const sourcemaps    = require('gulp-sourcemaps');
const rename        = require('gulp-rename');
const sass          = require('gulp-sass');
const cleanCSS      = require('gulp-clean-css');
const postcss       = require('gulp-postcss');
const autoprefixer  = require('autoprefixer');
const concat        = require('gulp-concat');
const svgo          = require('gulp-svgo');

const production    = !!gutil.env.prod;

// Build app.js
gulp.task('scripts', function () {
    return browserify({
        entries: 'web/layout/scripts/app.js',
        debug: !production
    })
        .bundle()
        .pipe(source('web/layout/scripts/app.js'))
        .pipe(buffer())
        .pipe(production ? uglify() : gutil.noop())
        .pipe(rename('app.js'))
        .on('error', gutil.log)
        .pipe(production ? sourcemaps.write() : gutil.noop())
        .pipe(gulp.dest('web/layout'));
});

// Build bundle.css
gulp.task('styles', function () {
    return gulp.src('web/layout/styles/app.scss')
        .pipe(production ? sourcemaps.init() : gutil.noop())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['> 5%']})]))
        .pipe(production ? sourcemaps.write() : gutil.noop())
        .pipe(production ? cleanCSS() : gutil.noop())
        .pipe(gulp.dest('web/layout'));
});

// Run SVG optimization
gulp.task('svgo', function () {
    return gulp.src('web/layout/images/*')
        .pipe(svgo())
        .pipe(gulp.dest('web/layout/images'));
});

// Watch task
gulp.task('watch', function () {
    gulp.watch(['web/layout/images/*.svg', 'web/layout/images/**/*.svg'], ['svgo']);
    gulp.watch(['web/layout/scripts/*.js', 'web/layout/scripts/**/*.js'], ['scripts']);
    gulp.watch(['web/layout/styles/*.scss', 'web/layout/styles/**/*.scss'], ['styles']);
});

// Build by default
gulp.task('default', ['build']);

// Build task
gulp.task('build', ['svgo', 'scripts', 'styles']);

// Build and watch task
gulp.task('build:watch', ['build', 'watch']);