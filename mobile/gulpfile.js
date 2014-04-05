var gulp = require('gulp'),
    log = require('gulp-util').log,
    rename = require('gulp-rename'),
    flatten = require('gulp-flatten'),
    jade = require('gulp-jade'),
    stylus = require('gulp-stylus'),    
    coffee = require('gulp-coffee');

gulp.task('templates', function() {
    var locs = {};     
    gulp.src('./src/index.jade')
        .pipe(jade({locals: locs}))
        .pipe(rename('index.html'))
        .pipe(gulp.dest('./'));
    gulp.src('./src/tpls/*.jade')
        .pipe(jade({locals: locs}))
        .pipe(gulp.dest('./assets'))
});
gulp.task('scripts', function() {
    gulp.src('./src/*.coffee')
        .pipe(coffee({bare: true}).on('error', log))
        .pipe(rename('app.js'))
        .pipe(gulp.dest('./assets'))          
});
gulp.task('styles', function() {
   gulp.src('./src/*.styl')
       .pipe(stylus().on('error', log))
       .pipe(rename('styles.css'))
       .pipe(gulp.dest('./assets'))
});
gulp.task('lib', function() {
    gulp.src('bower_components/**/*.min.*')
        .pipe(flatten())
        .pipe(gulp.dest('./assets/lib'))
});
gulp.task('watch', function() {
    log('Watching files');
    gulp.watch('./src/**/*', ['build']);
});

//define cmd line default task
gulp.task('build', ['templates', 'styles', 'scripts', 'lib']);
gulp.task('default', ['build', 'watch']);