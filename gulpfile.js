const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const ejs = require('gulp-ejs');
const rename = require('gulp-rename');
const imagemin = require('gulp-imagemin');
const imageminPnguant = require('imagemin-pngquant');
const imageminMozjpeg = require('imagemin-mozjpeg');
const webp = require('gulp-webp');
const browserSync = require('browser-sync').create();

// Sassのコンパイルタスク
const postcssOption = [ autoprefixer ]
gulp.task('sass', () => {
    return gulp.src('./src/sass/*.scss')
        .pipe(sass())
        .pipe(postcss(postcssOption))
        .pipe(gulp.dest('./dist/css'))
})

// jsのコピータスク
gulp.task('js', () => {
    return gulp.src('./src/js/*.js')
        .pipe(gulp.dest('./dist/js'));
})

// htmlのコピータスク
gulp.task('html', () => {
    return gulp.src('./src/**/*.html')
        .pipe(gulp.dest('./dist/'));
})

// phpのコピータスク
gulp.task('php', () => {
    return gulp.src('./src/**/*.php')
        .pipe(gulp.dest('./dist/'));
})

// 画像圧縮タスク
const imageminOption = [
    imageminPnguant({ quality: [0.65, 0.8] }),
    imageminMozjpeg({ quality: 80 }),
    imagemin.gifsicle(),
    imagemin.optipng(),
    imagemin.svgo()
]
gulp.task('imagemin', () => {
    return gulp.src('./src/images/*.{png,jpg,jpeg}')
        .pipe(webp())
        .pipe(gulp.dest('./dist/images'))
})

// ローカルサーバの起動
const browserSyncOption = {
    server: './dist'
}
gulp.task('serve', (done) => {
    browserSync.init(browserSyncOption)
    done()
})

// watchタスク
gulp.task('watch', (done) => {
    gulp.watch('./src/sass/**/*.scss', gulp.series('sass'));
    gulp.watch('./src/**/*.html', gulp.series('html'));
    gulp.watch('./src/js/**/*.js', gulp.series('js'));
    gulp.watch('./src/**/*.php', gulp.series('php'));
    gulp.watch('./src/images/*.{png,jpg,jpeg}', gulp.series('imagemin'));

    const browserReload = (done) => {
        browserSync.reload()
        done()
    }
    gulp.watch('./dist/**/*', browserReload)
})

// デフォルトタスク
gulp.task('default', gulp.series('sass', 'html', 'js', 'php', 'imagemin', 'serve', 'watch'))