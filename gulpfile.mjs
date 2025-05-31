// Importaciones necesarias para Gulp y tareas
import gulp from 'gulp';
import dartSass from "sass";
import gulpSass from "gulp-sass";
import cleanCSS from "gulp-clean-css";
import uglify from "gulp-uglify";
import concat from "gulp-concat";
import rename from "gulp-rename";
import sourcemaps from "gulp-sourcemaps";
import browserSyncLib from "browser-sync";
import imagemin from "gulp-imagemin";

// Inicialización de BrowserSync y Sass
const browserSync = browserSyncLib.create();
const sass = gulpSass(dartSass);

// Rutas utilizadas para los assets
const paths = {
  scss: './assets/scss/**/*.scss',      // Incluye popup.scss aquí
  js: './assets/js/**/*.js',            // popup.js debe estar en esta carpeta
  images: './assets/images/**/*',
  dist: './assets/dist'
};

// ✅ Tarea para procesar estilos SCSS (incluye popup.scss automáticamente)
function styles() {
  return gulp.src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(sass().on("error", sass.logError))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: ".min" }))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(`${paths.dist}/css`))
    .pipe(browserSync.stream());
}

// ✅ Tarea para procesar y concatenar JS (incluye popup.js automáticamente)
function scripts() {
  return gulp.src(paths.js)
    .pipe(sourcemaps.init())
    .pipe(concat("main.min.js")) // popup.js se combina en main.min.js
    .pipe(uglify())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(`${paths.dist}/js`))
    .pipe(browserSync.stream());
}

// Optimización de imágenes
function images() {
  return gulp.src(paths.images)
    .pipe(imagemin())
    .pipe(gulp.dest(`${paths.dist}/images`));
}

// ✅ BrowserSync + Watch para PHP, JS y SCSS
function serve() {
  browserSync.init({
    proxy: "http://localhost:10028", // Cambia por tu URL local
    notify: false
  });

  gulp.watch(paths.scss, styles);
  gulp.watch(paths.js, scripts);
  gulp.watch("./**/*.php").on("change", browserSync.reload);
}

// ✅ Exportación de las tareas
export default gulp.series(gulp.parallel(styles, scripts, images), serve);