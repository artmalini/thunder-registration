var gulp = require('gulp'),
      sass        = require('gulp-sass'),
      concatCss   = require('gulp-concat-css'),
	  sourcemaps  = require('gulp-sourcemaps'),
	  livereload  = require('gulp-livereload'),
	  prefix      = require('gulp-autoprefixer'),
	  plumber     = require('gulp-plumber'),
	  notify      = require('gulp-notify'),
	  uglify      = require('gulp-uglify'),
	  rename      = require('gulp-rename'),
	  imagemin    = require('gulp-imagemin'),
	  pngquant    = require('imagemin-pngquant'),
	  rimraf	  = require('rimraf'),	  
	  stripDebug  = require('gulp-strip-debug'),
      sass        = require('gulp-sass');

var path = {
    build: {
        html: 'out/',
        js: 'out/js/',
        css: 'admin/script/',
        //css: 'css/',
        img: 'out/img/',
        fonts: 'out/fonts/'
    },
    src: {
        html: 'src/*.html',
        js: 'assets/js/script.js',
        styles: 'admin/script/style.scss',
        //styles: 'css/layout1.scss',
        img: 'assets/images/*.*',
        fonts: 'src/fonts/**/*.*'
    },
    watch: {
        html: 'src/**/*.html',
        js: 'assets/js/**/*.js',
        styles: 'admin/script/**/*.scss',
        //styles: 'css/**/*.scss',
        img: 'assets/images/**/*.*',
        fonts: 'src/fonts/**/*.*'
    },
    clean: './out'
};

gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});

gulp.task('html:build', function () {
    gulp.src(path.src.html) 
    	.pipe(plumber())
        .pipe(gulp.dest(path.build.html))
        .pipe(livereload());
});

gulp.task('js:build', function () {
    gulp.src(path.src.js) 
    	.pipe(plumber())
        .pipe(sourcemaps.init()) 
        .pipe(uglify()) 
        .pipe(sourcemaps.write()) 
        .pipe(gulp.dest(path.build.js))
        //.pipe(notify({ message: 'Scripts task complete' }))
        .pipe(livereload());
});
gulp.task('style:build', function () {
    gulp.src(path.src.styles) 
        .pipe(plumber())
        .pipe(sourcemaps.init()) 
        .pipe(sass({includePaths: require('node-normalize-scss').includePaths}))
        .pipe(sass({outputStyle: 'nested'})) //nested, expanded, compact, compressed Скомпилируем
        .pipe(prefix()) //Добавим вендорные префиксы        
        .pipe(sourcemaps.write('.')) //in same folder
        .pipe(gulp.dest(path.build.css))        
        .pipe(livereload());
});

gulp.task('image:build', function () {
    gulp.src(path.src.img) 
    	.pipe(plumber())
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img))
        .pipe(livereload());
});

gulp.task('fonts:build', function() {
    gulp.src(path.src.fonts)
    	.pipe(plumber())
        .pipe(gulp.dest(path.build.fonts))
});

gulp.task('build', [
    //'html:build',
    'js:build',
    'style:build'
    //'fonts:build',
    //'image:build'
]);


gulp.task('watch', function(){
    gulp.watch([path.watch.html], ['html:build']);

    gulp.watch([path.watch.styles], ['style:build']);

    gulp.watch([path.watch.js], ['js:build']);

    gulp.watch([path.watch.img], ['image:build']);

    gulp.watch([path.watch.fonts], ['fonts:build']);
});
gulp.task('default', ['build', 'watch']);

/*gulp.task('styles', function () {
    gulp.src(paths.sassSrcPath)
        .pipe(sass({
            style: 'compressed',
            loadPath: [paths.sassImportsPath]
        }))
        .pipe(gulp.dest(paths.sassDestPath));
});

gulp.task('styles', function () {
    return sass(paths.sassSrcPath, {
            style: 'compressed',
            loadPath: [paths.sassImportsPath]
        })
        .pipe(gulp.dest(paths.sassDestPath));
});*/