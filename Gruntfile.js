module.exports = function(grunt) {

    grunt.initConfig({
        concat: {
            bundle: {
                src: [
                    'app/Resources/public/js/**/*.js',
                    'src/AppBundle/Resources/public/js/**/*.js',
                ],
                dest: 'web/assets/dest/js/scripts.js'
            },
        },
        uglify: {
            my_target: {
                files: {
                    'web/assets/dest/js/scripts.min.js': ['web/assets/dest/js/scripts.js']
                }
            }
        },
        cssmin: {
            target: {
                files: {
                    'web/assets/dest/css/app.min.css': [
                        'src/AppBundle/Resources/public/css/**/*.css',
                    ],
                }
            }
        },
        copy: {
            images: {
                expand: true, cwd: 'src/AppBundle/Resources/public/images', src: '**', dest: 'web/assets/images'
            },
        },
    });
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['concat:bundle', 'uglify', 'cssmin', 'copy']);
};
