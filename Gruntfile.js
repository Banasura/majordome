var ALL_TASKS;

ALL_TASKS = ['concat:all'];

module.exports = function(grunt) {
    var exec, path;
    path = require('path');
    exec = require('child_process').exec;

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.initConfig({
        pkg: '<json:package.json>',
        srcFolder: 'src',
        distFolder: 'dist',

        concat: {
            all: {
                files: {
                    'js/vendor.js': [
                        'bower_components/ie8-node-enum/index.js',
                        'bower_components/jquery-ui/ui/jquery.ui.core.js',
                        'bower_components/jquery-ui/ui/jquery.ui.widget.js',
                        'bower_components/jquery-ui/ui/jquery.ui.mouse.js',
                        'bower_components/jquery-ui/ui/jquery.ui.draggable.js',
                        'bower_components/jquery-ui/ui/jquery.ui.droppable.js',
                        'bower_components/jquery-ui/ui/jquery.ui.sortable.js',
                        'bower_components/jquery.scrollWindowTo/index.js',
                        'bower_components/underscore/underscore-min.js',
                        'bower_components/underscore.mixin.deepExtend/index.js',
                        'bower_components/rivets/dist/rivets.js',
                        'bower_components/backbone/backbone.js',
                        'bower_components/backbone-deep-model/src/deep-model.js'
                    ]
                }
            },
            mobile_friendly: {
                files: {
                    'js/vendor_mobile_friendly.js': [
                        'bower_components/ie8-node-enum/index.js',
                        'bower_components/jquery.scrollWindowTo/index.js',
                        'bower_components/underscore.mixin.deepExtend/index.js',
                        'bower_components/rivets/dist/rivets.js',
                        'bower_components/backbone-deep-model/src/deep-model.js'
                    ]
                }
            }
        },

        cssmin: {
            dist: {
                files: {
                    'css/admin.css': 'css/admin.css'
                }
            }
        },

        uglify: {
            dist: {
                files: {
                    'js/vendor.js': '<%= distFolder %>/vendor.js'
                }
            }
        },

        copy: {
            dist: {
                files: {
                    src: 'inc/**',
                    dest: 'dist/inc/'
                }
            }
        }
    });

    grunt.registerTask('default', ALL_TASKS);
    grunt.registerTask('mobile_friendly', ['concat:mobile_friendly']);
    return grunt.registerTask('dist', ['cssmin:dist', 'uglify:dist', 'copy:dist']);
};
