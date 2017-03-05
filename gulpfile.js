var gulp = require( "gulp" );
var del = require( "del" );
var $ = require( "gulp-load-plugins" )();
var browserSync = require( "browser-sync" ).create();

var vendorStyles = [];

var vendorScripts = [
	"bower_components/jquery/dist/jquery.js",
	"bower_components/bootstrap/dist/js/bootstrap.js",
	"bower_components/atk14js/src/atk14.js"
];

var applicationScripts = [
	"public/scripts/application.js"
];

// CSS
gulp.task( "styles", function() {
	return gulp.src( "public/styles/application.less" )
		.pipe( $.sourcemaps.init() )
		.pipe( $.less() )
		.pipe( $.autoprefixer() )
		.pipe( $.cleanCss() )
		.pipe( $.rename( { suffix: ".min" } ) )
		.pipe( $.sourcemaps.write( "." ) )
		.pipe( gulp.dest( "public/dist/styles" ) )
		.pipe( browserSync.stream( { match: "**/*.css" } ) );
} );

gulp.task( "styles-vendor", function() {
	return gulp.src( vendorStyles )
		.pipe( $.sourcemaps.init() )
		.pipe( $.concatCss( "vendor.css" ) )
		.pipe( $.autoprefixer() )
		.pipe( $.cleanCss() )
		.pipe( $.rename( { suffix: ".min" } ) )
		.pipe( $.sourcemaps.write( "." ) )
		.pipe( gulp.dest( "public/dist/styles" ) )
		.pipe( browserSync.stream( { match: "**/*.css" } ) );
} );

// JS
gulp.task( "scripts", function() {
	gulp.src( vendorScripts )
		.pipe( $.sourcemaps.init() )
		.pipe( $.concat( "vendor.js" ) )
		.pipe( $.uglify() )
		.pipe( $.rename( { suffix: ".min" } ) )
		.pipe( $.sourcemaps.write( "." ) )
		.pipe( gulp.dest( "public/dist/scripts" ) );

	gulp.src( applicationScripts )
		.pipe( $.sourcemaps.init() )
		.pipe( $.concat( "application.js" ) )
		.pipe( $.uglify() )
		.pipe( $.rename( { suffix: ".min" } ) )
		.pipe( $.sourcemaps.write( "." ) )
		.pipe( gulp.dest( "public/dist/scripts" ) );
} );

// Lint
gulp.task( "lint", function() {
	return gulp.src( [ "public/scripts/**/*.js", "gulpfile.js" ] )
		.pipe( $.jshint() )
		.pipe( $.jshint.reporter( "jshint-stylish" ) );
} );

// Code style
gulp.task( "jscs", function() {
	return gulp.src( [ "public/scripts/**/*.js", "gulpfile.js" ] )
		.pipe( $.jscs() );
} );

// Copy
gulp.task( "copy", function() {
	gulp.src( "bower_components/html5shiv/dist/html5shiv.min.js" )
		.pipe( gulp.dest( "public/dist/scripts" ) );
	gulp.src( "bower_components/respond/dest/respond.min.js" )
		.pipe( gulp.dest( "public/dist/scripts" ) );
	gulp.src( "bower_components/bootstrap/dist/fonts/*" )
		.pipe( gulp.dest( "public/dist/fonts" ) );
	gulp.src( "public/fonts/*" )
		.pipe( gulp.dest( "public/dist/fonts" ) );
	gulp.src( "public/images/*" )
		.pipe( gulp.dest( "public/dist/images" ) );
} );

// Clean
gulp.task( "clean", function() {
	del( "dist" );
} );

// Server
gulp.task( "serve", [ "styles" ], function() {
	browserSync.init( {
		proxy: "atk14book.localhost"
	} );

	// If these files change = reload browser
	gulp.watch( [
		"app/**/*.tpl",
		"public/images/**/*"
	] ).on( "change", browserSync.reload );

	// If javascript files change = run 'scripts' task, then reload browser
	gulp.watch( "public/scripts/**/*.js", [ "scripts" ] ).on( "change", browserSync.reload );

	// If styles files change = run 'styles' task with style injection
	gulp.watch( "public/styles/**/*.less", [ "styles" ] );
} );

// Build
var buildTasks = [
	"lint",
	"jscs",
	"styles",
	"styles-vendor",
	"scripts",
	"copy"
];

gulp.task( "build", buildTasks, function() {
	return gulp.src( "public/dist/**/*" )
		.pipe( $.size( { title: "build", gzip: true } ) );
} );

// Default
gulp.task( "default", [ "clean" ], function() {
	gulp.start( "build" );
} );
