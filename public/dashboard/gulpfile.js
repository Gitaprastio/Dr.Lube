"use strict";

// Load plugins
const autoprefixer = require("gulp-autoprefixer").default;
const browsersync = require("browser-sync").create();
const cleanCSS = require("gulp-clean-css");
const dartSass = require("sass");
const fs = require("fs");
const gulp = require("gulp");
const header = require("gulp-header");
const path = require("path");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const gulpSass = require("gulp-sass");
const uglify = require("gulp-uglify");
const sass = gulpSass(dartSass);

// Load package.json for banner
const pkg = require('./package.json');

// Set the banner content
const banner = ['/*!\n',
  ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
  ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
  ' * Licensed under <%= pkg.license %> (https://github.com/BlackrockDigital/<%= pkg.name %>/blob/master/LICENSE)\n',
  ' */\n',
  '\n'
].join('');

// BrowserSync
function browserSync(done) {
  browsersync.init({
    server: {
      baseDir: "./"
    },
    port: 3000
  });
  done();
}

// BrowserSync reload
function browserSyncReload(done) {
  browsersync.reload();
  done();
}

// Clean vendor
function clean(done) {
  fs.rmSync("./vendor", {
    recursive: true,
    force: true
  });
  done();
}

function copyDirectory(source, destination) {
  fs.cpSync(source, destination, {
    recursive: true
  });
}

function copyFiles(source, destination, predicate) {
  fs.mkdirSync(destination, {
    recursive: true
  });

  fs.readdirSync(source, {
    withFileTypes: true
  }).forEach((entry) => {
    if (predicate && !predicate(entry.name, entry)) {
      return;
    }

    fs.cpSync(path.join(source, entry.name), path.join(destination, entry.name), {
      recursive: true
    });
  });
}

// Bring third party dependencies from node_modules into vendor directory
function modules(done) {
  copyFiles("./node_modules/bootstrap/dist/js", "./vendor/bootstrap/js");
  copyDirectory("./node_modules/bootstrap/scss", "./vendor/bootstrap/scss");
  copyFiles("./node_modules/chart.js/dist", "./vendor/chart.js", (name) => name.endsWith(".js"));
  copyFiles("./node_modules/datatables.net/js", "./vendor/datatables", (name) => name.endsWith(".js"));
  copyFiles("./node_modules/datatables.net-bs4/js", "./vendor/datatables", (name) => name.endsWith(".js"));
  copyFiles("./node_modules/datatables.net-bs4/css", "./vendor/datatables", (name) => name.endsWith(".css"));
  copyDirectory("./node_modules/@fortawesome/fontawesome-free", "./vendor/fontawesome-free");
  copyFiles("./node_modules/jquery.easing", "./vendor/jquery-easing", (name) => name.endsWith(".js"));
  copyFiles("./node_modules/jquery/dist", "./vendor/jquery", (name) => name !== "core.js");
  done();
}

// CSS task
function css() {
  return gulp
    .src("./scss/**/*.scss")
    .pipe(plumber())
    .pipe(sass({
      outputStyle: "expanded",
      includePaths: "./node_modules",
    }))
    .on("error", sass.logError)
    .pipe(autoprefixer({
      overrideBrowserslist: ['last 2 versions'],
      cascade: false
    }))
    .pipe(header(banner, {
      pkg: pkg
    }))
    .pipe(gulp.dest("./css"))
    .pipe(rename({
      suffix: ".min"
    }))
    .pipe(cleanCSS())
    .pipe(gulp.dest("./css"))
    .pipe(browsersync.stream());
}

// JS task
function js() {
  return gulp
    .src([
      './js/*.js',
      '!./js/*.min.js',
    ])
    .pipe(uglify())
    .pipe(header(banner, {
      pkg: pkg
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('./js'))
    .pipe(browsersync.stream());
}

// Watch files
function watchFiles() {
  gulp.watch("./scss/**/*", css);
  gulp.watch(["./js/**/*", "!./js/**/*.min.js"], js);
  gulp.watch("./**/*.html", browserSyncReload);
}

// Define complex tasks
const vendor = gulp.series(clean, modules);
const build = gulp.series(vendor, gulp.parallel(css, js));
const watch = gulp.series(build, gulp.parallel(watchFiles, browserSync));

// Export tasks
exports.css = css;
exports.js = js;
exports.clean = clean;
exports.vendor = vendor;
exports.build = build;
exports.watch = watch;
exports.default = build;
