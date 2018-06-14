# 肥宅快乐水

快速根据自己定义好的项目模板，快速进行创建。

## 使用
#### 创建新项目
命令
```
cola template:new [模板名] [目录名]
```

例子
```
// 根据 php 模板快速创建一个 composer package 项目
cola template:new php cola
```

#### 查看可用模板
命令
```
cola template:list
```

#### 添加代码模板

命令
```
cola template:add [模板名] [github项目]
```

例子
```
cola template:add php https://github.com/zhangxiangliang/cola-composer-package-template
```

#### 删除代码模板

命令
```
cola template:delete [模板名]
```

例子
```
cola template:delete php
```
