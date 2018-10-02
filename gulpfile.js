var gulp 		= require('gulp'),
	sass 		= require('gulp-sass'),
	prefix 		= require('gulp-autoprefixer'),
	exec 		= require('gulp-exec'),
	replace 	= require('gulp-replace'),
	livereload 	= require('gulp-livereload'),
	concat 		= require('gulp-concat'),
	notify 		= require('gulp-notify'),
	beautify 	= require('gulp-beautify'),
	csscomb 	= require('gulp-csscomb'),
	//cmq 		= require('gulp-combine-media-queries'),
	gcmq 		= require('gulp-group-css-media-queries'),
	fs          = require('fs'),
	del 		= require('del');



var options = {
	silent: true,
	continueOnError: true // default: false
};

function logError( err, res ) {
	console.log( 'Sass failed to compile' );
	console.log( '> ' + err.file.split( '/' )[err.file.split( '/' ).length - 1] + ' ' + 'line ' + err.line + ': ' + err.message );
}

// styles related
gulp.task('styles-dev', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass().on( 'error', logError ))
		// .pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
		// .pipe(chmod(644))
		.pipe(gulp.dest('./', {"mode": "0644"}))
		.pipe(notify({message: 'Styles task complete'}))
		.pipe(livereload());
});

gulp.task('styles', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass().on( 'error', logError ))
		.pipe(prefix("last 2 versions", "> 1%", "ie 8", "ie 7"))
		.pipe(replace('/* autoprefixer: off */', ' '))
		.pipe(gcmq())
		.pipe(csscomb())
		// .pipe(chmod(644))
		.pipe(gulp.dest('./', {"mode": "0644"}));
});

gulp.task('styles-watch', function () {
	livereload.listen();
	return gulp.watch('assets/scss/**/*.scss', ['styles']);
});

gulp.task('watch', function () {
	gulp.watch('assets/scss/**/*.scss', ['styles-dev']);
});

// usually there is a default task for lazy people who just wanna type gulp
gulp.task('start', ['styles'], function () {
	// silence
});

gulp.task('server', ['styles'], function () {
	console.log('The styles and scripts have been compiled for production! Go and clear the caches!');
});


/**
 * Copy theme folder outside in a build folder, recreate styles before that
 */
gulp.task('copy-folder', ['styles'], function () {

	return gulp.src('./')
		.pipe(exec('rm -Rf ./../build; mkdir -p ./../build/jason; rsync -av --exclude="node_modules" ./* ./../build/jason/', options));
});

/**
 * Clean the folder of unneeded files and folders
 */
gulp.task('build', ['copy-folder'], function () {

	// files that should not be present in build
	files_to_remove = [
		'**/codekit-config.json',
		'node_modules',
		'config.rb',
		'gulpfile.js',
		'package.json',
		'pxg.json',
		'build',
		'css',
		'.idea',
		'**/.svn*',
		'**/*.css.map',
		'**/.sass*',
		'.sass*',
		'**/.git*',
		'*.sublime-project',
		'*.sublime-workspace',
		'.DS_Store',
		'**/.DS_Store',
		'__MACOSX',
		'**/__MACOSX',
		'README.md'
	];

	files_to_remove.forEach(function (e, k) {
		files_to_remove[k] = '../build/jason/' + e;
	});

	del.sync(files_to_remove, {force: true});
});

/**
 * Create a zip archive out of the cleaned folder and delete the folder
 */
gulp.task('zip', ['build'], function(){

	var versionString = '';
	//get theme version from styles.css
	var contents = fs.readFileSync("./style.css", "utf8");

	// split it by lines
	var lines = contents.split(/[\r\n]/);

	function checkIfVersionLine(value, index, ar) {
		var myRegEx = /^[Vv]ersion:/;
		if ( myRegEx.test(value) ) {
			return true;
		}
		return false;
	}

	// apply the filter
	var versionLine = lines.filter(checkIfVersionLine);

	versionString = versionLine[0].replace(/^[Vv]ersion:/, '' ).trim();
	versionString = '-' + versionString.replace(/\./g,'-');

	return gulp.src('./')
	           .pipe(exec('cd ./../; rm -rf Jason*.zip; cd ./build/; zip -r -X ./../Jason-Installer' + versionString +'.zip ./; cd ./../; rm -rf build'));

});

// usually there is a default task  for lazy people who just wanna type gulp
gulp.task('default', ['start'], function () {
	// silence
});

/**
 * Short commands help
 */

gulp.task('help', function () {

	var $help = '\nCommands available : \n \n' +
		'=== General Commands === \n' +
		'start              (default)Compiles all styles and scripts and makes the theme ready to start \n' +
		'zip               	Generate the zip archive \n' +
		'build						  Generate the build directory with the cleaned theme \n' +
		'help               Print all commands \n' +
		'=== Style === \n' +
		'styles             Compiles styles in production mode\n' +
		'styles-dev         Compiles styles in development mode \n' +
		'=== Scripts === \n' +
		'scripts            Concatenate all js scripts \n' +
		'scripts-dev        Concatenate all js scripts and live-reload \n' +
		'=== Watchers === \n' +
		'watch              Watches all js and scss files \n' +
		'styles-watch       Watch only styles\n' +
		'scripts-watch      Watch scripts only \n';

	console.log($help);

});