var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var concatCss = require('gulp-concat-css');
var uglify = require('gulp-uglify-es').default;
var cleanCSS = require('gulp-clean-css');
var gulpCopy = require('gulp-copy');
var shell = require('gulp-shell');

gulp.task('shell_composer_self_update', shell.task('composer self-update'));
gulp.task('shell_composer_update', shell.task('composer update'));
gulp.task('shell_npm_update', shell.task('npm update'));
gulp.task('shell_test', shell.task('vendor\\bin\\tester tests -s -p php'));
gulp.task('shell_deploy_prod', shell.task('cd deployment & deployment-prod.bat'));
gulp.task('shell_deploy_test', shell.task('cd deployment & deployment-test.bat'));
gulp.task('shell_netteCodeChecker', shell.task('php ..\\nette-code-checker\\code-checker -d app --short-arrays --strict-types'));
gulp.task('shell_netteCodeCheckerFIX', shell.task('php ..\\nette-code-checker\\code-checker -d app --short-arrays --strict-types --fix'));
gulp.task('shell_netteCodingStandard', shell.task('php ..\\nette-coding-standard\\ecs check app tests --preset php71'));
gulp.task('shell_netteCodingStandardFIX', shell.task('php ..\\nette-coding-standard\\ecs check app tests --preset php71 --fix'));

gulp.task('sass', done => {
    gulp.src(['src/scss-admin/fontawesome.scss'])
        .pipe(sass({includePaths: ['./node_modules/@fortawesome/fontawesome-free/scss']}))
        .pipe(concat('fontawesome.css'))
        .pipe(gulp.dest('src/css/tmp'));

    gulp.src(['src/scss/main.scss'])
        .pipe(sass({includePaths: ['./node_modules/bootstrap/scss', './node_modules/bootstrap/scss/mixins']}))
        .pipe(cleanCSS())
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest('www/css'));

    gulp.src(['src/scss-admin/main.scss'])
        .pipe(sass({includePaths: ['./node_modules/bootstrap/scss', './node_modules/bootstrap/scss/mixins']}))
        .pipe(cleanCSS())
        .pipe(concat('admin.min.css'))
        .pipe(gulp.dest('www/css'));

    done();
});

gulp.task('jsMain', done => {
    gulp
        .src(['node_modules/jquery/dist/jquery.min.js',
            'node_modules/popper.js/dist/umd/popper.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/tether/dist/js/tether.min.js',
            'node_modules/naja/dist/Naja.js',
            'venor/nette/forms/src/assets/netteForms.min.js',
            'src/js/main.js'], {allowEmpty: true})
        .pipe(uglify({
            mangle: false,//disable rename variables and functions
        }))
        .pipe(concat('main.min.js'))
        .pipe(gulp.dest('www/js'));

    done();
});

gulp.task('jsAdmin', done => {
    gulp.src(['node_modules/jquery/dist/jquery.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/tether/dist/js/tether.min.js',
        'venor/nette/forms/src/assets/netteForms.min.js',
        'src/js/admin.js'], {allowEmpty: true})
        .pipe(uglify({
            mangle: false,//disable rename variables and functions
        }))
        .pipe(concat('admin.min.js'))
        .pipe(gulp.dest('www/js'));

    done();
});

gulp.task('copy', done => {
    //font awesome
    gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/*')
        .pipe(gulp.dest('www/fonts'));

    done();
});


gulp.task('sass:watch', function () {
    gulp.watch(['src/scss/*.scss', 'src/scss/*/*.scss'], gulp.series('sass'));
});


gulp.task('jsAll', gulp.series(['jsMain', 'jsAdmin']));
gulp.task('default', gulp.series('jsAll', 'sass'));
gulp.task('release', gulp.series(['copy', 'default']));
gulp.task('deploy-test', gulp.series(['release', 'shell_deploy_test']));
gulp.task('deploy-prod', gulp.series(['release', 'shell_deploy_prod']));
gulp.task('upgrade', gulp.series(['shell_composer_self_update', 'shell_composer_update', 'shell_npm_update', 'release', 'shell_test', 'shell_deploy_prod']));
