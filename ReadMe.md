# 克莱登大学在线选课系统 - Online Course Selection System of Carleton University

PHP课设：克莱登大学在线选课系统 - Online Course Selection System of Carleton University

**任何人不得将本Repository用于除作者以外任何人的《动态网页设计》课程设计作业。**

[TOC]

## 开发环境

- Windows 10  Home  64-bit
- PHP 7.3.8 (cli) (built: Jul 30 2019 12:44:06) ( ZTS MSVC15 (Visual C++ 2017) x64 )
- Apache/2.4.39 (Win64)
- Firefox 76.0.1 (64 位)
- MySQL  5.5.60

## 如何使用

您可以使用`git clone`：`git clone https://github.com/Foldblade/Online-Course-Selection-System-of-Carleton-University.git`，或前往[Github页面](https://github.com/Foldblade/Online-Course-Selection-System-of-Carleton-University)、[Github Release页面](https://github.com/Foldblade/Online-Course-Selection-System-of-Carleton-University/releases)下载源代码。

下载完成后，请将源代码放到您的网页目录内，然后拷贝`.sql_config.json.bak`为`.sql_config.json`，并对其中的配置进行修改：

```
{
    "host": "127.0.0.1", // 您的数据库地址
    "user": "root", // 您的数据库用户名
    "password": "YOUR PASSWORD", // 该用户的密码
    "database": "carleton" // 所选用的数据库，默认为carleton
}
```

为了您数据库的安全，请在Web服务器中配置禁止访问以`.`开头的文件。

您还需要新建一个同`.sql_config.json`中`database`字段名称一致的数据库（如保持默认则应新建一个名为`carleton`的数据库），并向其中存放入数据库文件。您可以在`/sql`目录下找到数据库文件`carleton.sql`。

## 数据库结构

### course表

用以存储课程信息。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| courseID     | int(10)     | UNSIGNED Primary Key | 课程ID         |
| name         | varchar(30) | not null             | 课程名         |
| name_en      | varchar(80) | not null             | 英文课程名     |
| score        | int(3)      | not null             | 学分           |
| theoryTime   | int(3)      | not null             | 理论学时       |
| practiceTime | int(3)      | not null             | 实践学时       |
| totalTime    | int(3)      | not null             | 总学时         |
| attribution  | varchar(20) | not null             | 归属院系       |
| language     | varchar(10) | not null             | 授课语言       |
| type         | varchar(3)  | not null             | 类型           |
| category     | varchar(10) | null                 | 校选课类别     |
| brief        | text        | null                 | 课程描述       |
| img          | varchar(50) | null                 | 课程配图文件名 |

### user表

用以存储用户数据。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| userID     | bigint(20)    | UNSIGNED Primary Key | 用户ID   |
| user     | varchar(10) | not null | 用户名      |
| privilege | varchar(20) | not null | 用户权限 |
| score        | char(40) | not null | SHA-1加密后的密码 |
| salt | char(36) | not null | SHA-1 SALT |

### loginFail表

用以记录登陆失败次数以防止暴力破解密码。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| logID  | bigint(20)    | UNSIGNED Primary Key | ID   |
| userName | varchar(10) | not null | 用户名      |
| time | varchar(20) | not null | UNIX秒数时间戳，失效时间 |

### status表

登陆状态表。用以校验用户是否处于登陆状态。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| userID | bigint(20)    | UNSIGNED Primary Key | 用户ID   |
| user | varchar(10) | not null | 用户名      |
| GUID | char(36) | not null | 签发的作为登陆凭据的GUID |
| expireTime | varchar(20) | not null | UNIX秒数时间戳，失效时间 |
| statusID | varchar(20) | UNSIGNED not null | 状态编号 |

### selectedCourse表

选课表。存储用户选课数据。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| logID | bigint(20)    | UNSIGNED Primary Key | ID   |
| userID | bigint(20) | not null | 用户ID    |
| courseID | int(11) | not null | 课程ID |
| updateTime | timestamp | on update CURRENT_TIMESTAMP | 更新时间 |

### auditQuery表

审核队列表。存储教秘审核状态。

| 字段         | 类型        | 属性                 | 备注           |
| ------------ | ----------- | -------------------- | -------------- |
| logID | bigint(20)    | UNSIGNED Primary Key | ID   |
| userID | bigint(20) | not null | 用户ID    |
| audited | int(11) | null | 是否审核通过 |
| updateTime | timestamp | on update CURRENT_TIMESTAMP | 更新时间 |

## 功能展示

### 测试账号

学生账号：STU18001 密码：STU18001

教学秘书账号：J18001 密码：J18001

### 登录页

![登录页](ReadMe.assets\image-20200606185925218.png)

除登录页、关于页外的所有页面均需登陆后方可访问。如未登陆而访问了除登录页、关于页外的页面，将会跳转到登录页。

在登陆页可选是否保持登录状态。若登录失败会出现失败提示，失败超过三次将会在24小时内禁止登录。

![登录页2](ReadMe.assets\image-20200606190227772.png)

### 课程库（首页）

登陆成功后将会自动跳转到首页。首页无论是学生或教学秘书都可访问。

![课程库（首页）](ReadMe.assets\image-20200606192731898.png)

在首页可以按条件进行课程的查询。

![课程库（首页）2](ReadMe.assets\image-20200606192833888.png)

点击课程列表右侧的详情按钮，则可在新标签页中打开课程详情页，查看课程的详细信息。

### 课程详情页

可在此页查看到课程的详细信息。

![课程详情页](ReadMe.assets\image-20200606192928126.png)

### 我的选课页

该页面仅学生可以访问。

![我的选课页](ReadMe.assets\image-20200606193404698.png)

学生可在我的选课页查询心仪的课程，并进行选课。选课后可进行删除操作，并可在此向教学秘书提交审核申请。

### 选课结果页

该页面仅学生可以访问。

学生可以在该页面查看到审核进度和自己的选课结果。

![选课结果页](ReadMe.assets\image-20200606195907475.png)

![选课结果页2](ReadMe.assets\image-20200606200421860.png)

### 选课审核页

该页面仅教学秘书可访问。

![选课审核页](ReadMe.assets\image-20200606200322236.png)

可在该页面批准或打回学生的选课申请。

### 已审核列表页

该页面仅教学秘书可访问。

![已审核列表页](ReadMe.assets\image-20200606200758289.png)

可在当前页面查看已审核过的申请。

### 课程管理页

该页面仅教学秘书可访问。

![课程管理页](ReadMe.assets\image-20200606201111925.png)

![课程管理页](ReadMe.assets\image-20200606201237578.png)

可在此添加课程或编辑现有课程信息。如点击编辑课程，将会在新标签页打开编辑课程页。

### 编辑课程页

该页面仅教学秘书可访问。

可在此页进行课程的编辑。

![编辑课程页](ReadMe.assets\image-20200606201420003.png)

### 账号管理页

该页面仅教学秘书可访问。

可在此页添加账号、编辑或删除现有账号。

![账号管理页](ReadMe.assets\image-20200606201510430.png)

![账号管理页2](ReadMe.assets\image-20200606201526802.png)

![账号管理页3](ReadMe.assets\image-20200606201542340.png)

![账号管理页4](ReadMe.assets\image-20200606201559978.png)

### 关于页

对本系统所用外部资源的致谢。

![关于页](ReadMe.assets\image-20200606201715082.png)

## 致谢

本次课设的完成，离不开[MDUI](https://www.mdui.org/)这一前端框架的精美样式；同时，感谢钱锺书先生的《围城》为本次课设的命名提供了启发。

此外，本次课设中亦用到了一些图片，在此向以下图片作者致谢：

- 登录页背景图：Photo by Jordan Encarnacao on Unsplash
- 404页用图：Image by Andrew Martin from Pixabay
- 课程详情页默认背景图：Free-Photos from Pixabay
- “货币银行学(A)”课程测试用背景图：Photo by Josh Appel on Unsplash