require("babel-polyfill");
const path = require('path');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const { VueLoaderPlugin } = require('vue-loader')

var config = [
    {
        name: 'app',
        entry: {
            app: ['babel-polyfill', './app/app.js', './scss/app.scss'],
            composer: './app/composer.js',
            dashboard: './app/dashboard.js',
            form: './app/form.js',
            datagrid: './app/datagrid.js',
            unsubscribe: './app/unsubscribe.js',
            preview: './app/preview.js'
        },
        output: {
            path: path.resolve(__dirname, "dist"),
            filename: "[name].js",
            publicPath: "./"
        },
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js'
            },
            extensions: ['*', '.js', '.vue', '.json']
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    use: 'vue-loader'
                },
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader'
                    }
                },
                {
                    test: /\.scss$/,
                    use: [  
                        {
                            loader: 'file-loader',
                            options: {
                                name: '[name].css',
                                context: './',
                                outputPath: '/',
                                publicPath: '/dist'
                            }
                        },
                        {
                            loader: 'extract-loader'
                        },
                        {
                            loader: 'css-loader',
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: true
                            }
                        }
                    ]
                },
                {
                    test: /\.svg$/,
                    loader: 'svg-sprite-loader',
                },
                {
                    test: /\.(woff2|woff|ttf|eot|otf)$/,
                    use: [{
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/',
                            context: 'dist'
                        }
                    }]
                },
                {
                    test: /\.(png|jpg|gif)$/,
                    use: [{
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'img/',
                            context: 'dist'
                        }
                    }]
                }
            ]
        },
        optimization: {
            splitChunks: {
                chunks: 'all',
                name: 'vendor',
                minChunks: 2
            },
        },
        performance: {
            maxEntrypointSize: 512000,
            maxAssetSize: 512000
        },
        plugins: [
            new VueLoaderPlugin(),
            new webpack.ProvidePlugin({
                Noty: 'noty'
            }),
        ]
    }, 
]

module.exports = (env, argv) => {
    if (argv.mode === 'production') {
        new UglifyJsPlugin({
            test: /\.js(\?.*)?$/i,
        })
    }

    return config;
};