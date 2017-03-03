var gulp = require('gulp');
var concat = require('gulp-concat');
var minify = require('gulp-minify-css');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var replace = require('gulp-replace');

// Bootstrap scss source
var bootstrapSass = {
    in: 'node_modules/bootstrap-sass/'
};
var fontawesomeSass = {
    in: 'node_modules/font-awesome/'
};

// source and distribution folder
var source = 'assets/css/',
    dest = 'assets/';
var fonts = {
    in: [source + 'fonts/*.*',
        bootstrapSass.in + 'assets/fonts/**/*',
        fontawesomeSass.in + 'fonts/*'
    ],
    out: dest + 'fonts/'
};

var scss = {
    in: ['assets/css/scss/base.scss',
        'node_modules/fancybox/dist/scss/jquery.fancybox.scss',
        'assets/css/scss/components/**/*.scss'],
    sassOpts: {
        includePaths: [bootstrapSass.in + 'assets/stylesheets']
    }
};
var js = {
    in: ['assets/js/components/*.js',
        'node_modules/fancybox/dist/js/jquery.fancybox.js',
        'assets/js/components/**/*.js']
};
var adminjs = {
    in: ['assets/js/admin/*.js',
        'assets/js/admin/**/*.js']
};

gulp.task('styles', ['fonts'], function() {
    gulp.src('assets/css/scss/base.scss')
        .pipe(sass(scss.sassOpts).on('error', sass.logError))
        .pipe(concat('plugin.min.css'))
        .pipe(minify())
        .pipe(replace('../img/', '../imgs/'))
        .pipe(gulp.dest('assets/css'));
});
// copy bootstrap required fonts to dest
gulp.task('fonts', function () {
    return gulp
        .src(fonts.in)
        .pipe(gulp.dest(fonts.out));
});

gulp.task('javascripts', function() {
    gulp.src(js.in)
        .pipe(concat('plugin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js'));
    gulp.src(adminjs.in)
        .pipe(concat('pluginadmin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js'));
});


gulp.task('watch', function() {
    gulp.watch(scss.in,
        ['styles']);
    gulp.watch(js.in,
        ['javascripts']);
    gulp.watch(adminjs.in,
        ['javascripts']);
});

gulp.task('default', ['styles', 'javascripts', 'watch']);