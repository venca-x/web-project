var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass')(require('sass'));
var uglify = require('gulp-uglify-es').default;
var cleanCSS = require('gulp-clean-css');
var shell = require('gulp-shell');

gulp.task('shell_composer_self_update', shell.task('composer self-update'));
gulp.task('shell_composer_update', shell.task('composer update'));
gulp.task('shell_npm_update', shell.task('npm update'));
gulp.task('shell_test', shell.task('vendor\\bin\\tester tests -s -p php'));
gulp.task('shell_deploy_prod', shell.task('cd deployment & deployment-prod.bat'));
gulp.task('shell_deploy_test', shell.task('cd deployment & deployment-test.bat'));
gulp.task('shell_netteCodeChecker', shell.task('php ..\\nette-code-checker\\code-checker -d app --short-arrays --strict-types'));
gulp.task('shell_netteCodeCheckerFIX', shell.task('php ..\\nette-code-checker\\code-checker -d app --short-arrays --strict-types --fix'));
gulp.task('shell_netteCodingStandard', shell.task('php ..\\nette-coding-standard\\ecs check app tests --preset php81'));
gulp.task('shell_netteCodingStandardFIX', shell.task('php ..\\nette-coding-standard\\ecs check app tests --preset php81 --fix'));
gulp.task('shell_phpstan', shell.task('c:\\www\\phpstan-nette\\vendor\\bin\\phpstan.bat analyse app tests --level=6 --memory-limit=4000M'));

gulp.task('sass', done => {
    gulp.src(['src/scss-admin/fontawesome.scss'])
        .pipe(sass({includePaths: ['./node_modules/@fortawesome/fontawesome-free/scss']}).on('error', sass.logError))
        .pipe(concat('fontawesome.css'))
        .pipe(gulp.dest('src/css/tmp'));

    gulp.src(['src/scss-admin/fontawesome-brands.scss'])
        .pipe(sass({includePaths: ['./node_modules/@fortawesome/fontawesome-free/scss']}).on('error', sass.logError))
        .pipe(concat('fontawesome-brands.css'))
        .pipe(gulp.dest('src/css/tmp'));

    gulp.src(['src/scss-admin/fontawesome-solid.scss'])
        .pipe(sass({includePaths: ['./node_modules/@fortawesome/fontawesome-free/scss']}).on('error', sass.logError))
        .pipe(concat('fontawesome-solid.css'))
        .pipe(gulp.dest('src/css/tmp'));

    gulp.src(['src/scss/main.scss'])
        .pipe(sass({
            includePaths: ['./node_modules/bootstrap/scss',
                './node_modules/bootstrap/scss/forms',
                './node_modules/bootstrap/scss/helpers',
                './node_modules/bootstrap/scss/mixins',
                './node_modules/bootstrap/scss/utilities',
                './node_modules/bootstrap/scss/vendor']
        }).on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest('www/css'));

    gulp.src(['src/scss-admin/main.scss'])
        .pipe(sass({
            includePaths: ['./node_modules/bootstrap/scss',
                './node_modules/bootstrap/scss/forms',
                './node_modules/bootstrap/scss/helpers',
                './node_modules/bootstrap/scss/mixins',
                './node_modules/bootstrap/scss/utilities',
                './node_modules/bootstrap/scss/vendor']
        }).on('error', sass.logError))
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
            'vendor/nette/forms/src/assets/netteForms.min.js',
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
        'vendor/nette/forms/src/assets/netteForms.min.js',
        'src/js/admin.js'], {allowEmpty: true})
        .pipe(uglify({
            mangle: false,//disable rename variables and functions
        }))
        .pipe(concat('admin.min.js'))
        .pipe(gulp.dest('www/js'));

    done();
});

gulp.task('copy', function () {
    return gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/**/*', {
        encoding: false,
    })
    .pipe(gulp.dest('www/webfonts'));
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
