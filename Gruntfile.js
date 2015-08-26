var ALL_TASKS;

ALL_TASKS = ['concat:all'];

module.exports = function(grunt) {
    var exec, path;
    path = require('path');
    exec = require('child_process').exec;

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.initConfig({
        pkg: '<json:package.json>',
        srcFolder: 'src',
        distFolder: 'dist',
        vendorFolder: 'vendor',

        concat: {
            all: {
                files: {
                    '<%= vendorFolder %>/vendor.js': [
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
                    ],
                    '<%= vendorFolder %>/vendor.css': [
                        'bower_components/font-awesome/css/font-awesome.css'
                    ]
                }
            },
            mobile_friendly: {
                files: {
                    '<%= vendorFolder %>/js/vendor_mobile_friendly.js': [
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
                    '<%= vendorFolder %>/vendor.css': 'bower_components/font-awesome/css/font-awesome.css'
                }
            }
        },

        uglify: {
            dist: {
                files: {
                    '<%= vendorFolder %>/vendor.js': '<%= distFolder %>/vendor.js'
                }
            }
        }
    });

    grunt.registerTask('default', ALL_TASKS);
    grunt.registerTask('mobile_friendly', ['concat:mobile_friendly']);
    return grunt.registerTask('dist', ['cssmin:dist', 'uglify:dist']);
};
