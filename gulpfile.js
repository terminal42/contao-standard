'use strict';

const gulp = require('gulp');
const sftp = require('gulp-sftp');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const imagemin = require('gulp-imagemin');
const eslint = require('gulp-eslint');
const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');

const remoteConfig = {
    host: '%HOSTNAME%',
    user: '%USERNAME%',
    remotePath: '/home/%USERNAME%/public_html/current/web/layout'
};

// ESLint
gulp.task('lint', function () {
    return gulp.src(['layout/scripts/*.js', 'layout/scripts/**/*.js'])
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError());
});

// Build JavaScript
gulp.task('scripts', function () {
    return browserify({entries: 'layout/scripts/app.js'})
    .transform(babelify)
    .bundle()
    .pipe(source('layout/scripts/app.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(rename('app.js'))
    .pipe(gulp.dest('web/layout'));
});

// Build SCSS
gulp.task('styles', function () {
    return gulp.src('layout/styles/[^_]*.scss', {base: 'layout/styles'})
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(postcss([autoprefixer({browsers: ['> 5%']})]))
    .pipe(sourcemaps.write())
    .pipe(cleanCSS({restructuring: false}))
    .pipe(gulp.dest('web/layout'));
});

// Run image optimization
gulp.task('images', function () {
    gulp.src(['layout/images/*', 'layout/images/**/*'], {base: 'layout/images'})
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
    .pipe(gulp.dest('web/layout/images'));
});

// Copy fonts
gulp.task('fonts', function () {
    return gulp.src('layout/fonts/**/*')
    .pipe(gulp.dest('web/layout/fonts'));
});

gulp.task('deploy', function() {
    gulp.src(['web/layout/*', 'web/layout/**/*'])
    .pipe(sftp(remoteConfig));
});

// Watch task
gulp.task('watch', function () {
    gulp.watch(['layout/images/**/*'], ['images']);
    gulp.watch(['layout/scripts/*.js', 'layout/scripts/**/*.js'], ['scripts']);
    gulp.watch(['layout/styles/*.scss', 'layout/styles/**/*.scss'], ['styles']);
});

// Build by default
gulp.task('default', ['build']);

// Build task
gulp.task('build', ['lint', 'images', 'scripts', 'styles', 'fonts']);

// Build and watch task
gulp.task('build:watch', ['images', 'scripts', 'styles', 'fonts', 'watch']);

// Watch & Deploy task
gulp.task('build:watch:deploy', ['build:watch'], function () {
    gulp.watch(['web/layout/*.css', 'web/layout/*.js'], function(result) {
        gulp.src([result.path]).pipe(sftp(remoteConfig));
    });
});
