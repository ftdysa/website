var gulp = require('gulp');
var less = require('gulp-less');

gulp.task('less', function() {
    return gulp.src('src/Resources/less/site.less')
        .pipe(less())
        .pipe(gulp.dest('web/css'));
});

gulp.task('copy-js', function() {
   return gulp.src('bower_components/bootstrap/dist/js/bootstrap.min.js')
       .pipe(gulp.dest('web/js'));
});

gulp.task('copy-fonts', function() {
    return gulp.src('bower_components/font-awesome/fonts/*')
        .pipe(gulp.dest('web/fonts'));
})

gulp.task('default', ['less', 'copy-js', 'copy-fonts'], function() {
    var lessWatcher = gulp.watch('src/Resources/less/*.less', ['less']);
    lessWatcher.on('change', function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });
});