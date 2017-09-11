'use strict';

const gulp = require('gulp');
const fs = require('fs');
const config = JSON.parse(fs.readFileSync('./gulpfile.config.json'));

const browserify = require('browserify');
const tap = require('gulp-tap');
const babelify = require('babelify');
const buffer = require('vinyl-buffer');
const uglify = require('gulp-uglify');

const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cleanCSS = require('gulp-clean-css');

const imagemin = require('gulp-imagemin');

// Compile scripts
gulp.task('scripts', function () {
    return gulp.src(config.scripts.sourceFiles, {read: false}) // no need of reading file because browserify does.
    .pipe(tap(function (file) {
        file.contents = browserify(file.path, {debug: true}).transform(babelify).bundle();
    }))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest(config.scripts.targetFolder));
});

// Compile styles
gulp.task('styles', function () {
    return gulp.src(config.styles.sourceFiles)
    .pipe(sass())
    .pipe(postcss([autoprefixer({browsers: ['> 5%']})]))
    .pipe(cleanCSS(config.styles.cleanCss))
    .pipe(gulp.dest(config.styles.targetFolder))
});

// Run image optimization
gulp.task('images', function () {
    gulp.src(config.images.sourceFiles)
    .pipe(imagemin([
        imagemin.gifsicle(),
        imagemin.jpegtran(),
        imagemin.optipng(),
        imagemin.svgo(function (file) {
            var prefix = path.basename(file.relative, path.extname(file.relative));

            // Ensure the ID attributes in SVG files are unique
            return {
                plugins: [{
                    cleanupIDs: {
                        prefix: prefix + '-',
                        minify: true
                    }
                }]
            }
        })
    ]))
    .pipe(gulp.dest(config.images.targetFolder))
});

// Build by default
gulp.task('default', ['images', 'scripts', 'styles']);
