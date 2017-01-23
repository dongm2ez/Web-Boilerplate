# Laravel web boilerplate

laravel 框架5.3搭建的样板项目

包含前后端分离web网站的一些基础配置，以减少每次初始化框架都要配置的繁琐工作

## 结构

### 路由

    routes
    - api.php api路由
    - console.php 控制台命令
    - web 网站路由

### 数据表迁移

    database
    - migrations 表迁移

### 配置文件

    config
    - jwt json web token 配置文件，主要配置前后端交互的token的一些参数

### 应用目录

    app 这个目录放网站主要的
    - Console 命令行相关
    - Exceptions 异常相关
    - Http 控制器和中间件（过滤器）
      - Controllers 控制器目录
        - Controller.php 基类控制器
        - AccountController.php 账号相关的控制器（已经实现jwt的Auth基本操作，按需改造）
      - Middleware 中间件目录
      Kernel.php 中间件配置文件
    - Models 模型
    - Providers 服务供应
    Helpers.php 全局函数放在此文件

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
