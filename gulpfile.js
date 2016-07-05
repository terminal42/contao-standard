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
const install       = require('gulp-install');
const runSequence   = require('run-sequence');

const production    = !!gutil.env.production;

// npm install
gulp.task('npm-install', function () {
    return gulp.src('./package.json')
        .pipe(install());
});

// Build app.js
gulp.task('scripts', function () {
    return browserify({
        entries: './web/layout/scripts/app.js',
        debug: !production
    })
        .bundle()
        .pipe(source('./web/layout/scripts/app.js'))
        .pipe(buffer())
        .pipe(production ? uglify() : gutil.noop())
        .pipe(rename('app.js'))
        .on('error', gutil.log)
        .pipe(production ? sourcemaps.write() : gutil.noop())
        .pipe(gulp.dest('./web/layout'));
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

// Build by default
gulp.task('default', ['scripts', 'styles']);

// Watch task
gulp.task('watch', function () {
    gulp.watch(['./web/layout/scripts/*.js', './web/layout/scripts/**/*.js'], ['scripts']);
    gulp.watch(['./web/layout/styles/*.scss', './web/layout/styles/**/*.scss'], ['styles']);
});

// Build task
gulp.task('build', ['scripts', 'styles']);

// Build and watch task
gulp.task('build:watch', ['build', 'watch']);
gulp.task('update:build:watch', ['update', 'build', 'watch', 'watch-update']);

// Update task
gulp.task('update', function () {
    runSequence(
        'npm-install',
        'build'
    );
});