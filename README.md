[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg)]()

![majordome-logo2](https://cloud.githubusercontent.com/assets/394565/10220817/458663c8-684a-11e5-89e8-36d69694786d.png)

# Majordome
**A Dotclear plugin which allow user to easily create and manage user forms.**

## Installing the plugin
Majordome is distributed as a Dotclear plugin. All the code is bundled in a .zip
file that you will have to upload to your Dotclear blog.

*Note: this plugin has no stable version yet. Do not use it in a production
environment!*

At this time, there is no stable release of the plugin, so you will have to
build the .zip yourself. Fortunately, this process is super simple as you just
have to run the build script. You will need Grunt, Bower and NPM to do so:

1. Download the sources and the Formbuilder dependency
```bash
git clone https://github.com/Banasura/majordome.git
git submodule init
git submodule update
```

2. Download the dependencies
```bash
npm install
bower install
```

3. Build everything!
```bash
grunt dist
```

That all. You will then find a `majordome.zip` file inside the `/dist` folder.
Upload it in your Doclear blog and enjoy this plugin!

## Contributing
The development process is mainly done in the `dev` branch of this repository.
To get involved in the plugin development, clone this repository and its submodule
using the commands written in the section *Installing the plugin*. Then,
switch to the `dev` branch to get the latest version of the plugin.

You can build the development version of the plugin by running `grunt` in the
Majordome folder. To test it on a Dotclear blog, use a local server such as
XAMPP and create a symlink from your Majordome folder to the Dotclear's plugins
folder:

```bash
ln -s /path/to/majordome /path/to/dotclear/plugins/majordome
```

## Thanks ##

This project use some resources from other people.

- The original Majordome's icon comes from [Jozef Krajčovič](http://jozefkrajcovic.sk/)
- The form builder is an original work of [dobtco](https://github.com/dobtco/formbuilder),
partially rewritten to fit the needs of Majordome

## License

This project is distributed under the **MIT** license. You can read more about
this in the `LICENSE` file of this project.
