# ff-api-wordpress

## Install
Download this repo into [project folder]/wp-content/plugins

## What this plugin will do for you
This module will add 3 endpoints to your project:

#### 1. Collections
/wp-json/ff/v1/collections?collection-types[]=[category slug]
>This endpoint will return an object. Each key is a slug of a category that has been added to the api. Each value is an array of ids pertaining to published posts in the category.
```
{
    category1: [
        1,
        2
    ],
    category3: [
        1
    ]
}
```

#### 2. Content
/wp-json/ff/v1/content
>This endpoint will return an object. Each key is a post id contained in the collections endpoint. Each value is an object representing all the data for the post. If the Advanced Custom Field plugin has been enabled, the post data will also contain any ACF's that have been attached.
```
{
    1: {
        ID: 1,
        post_author: "1",
        post_date: "2019-02-27 01:28:10",
        post_date_gmt: "2019-02-27 01:28:10",
        post_content: "Sample Content",
        post_title: "Sample Title",
        post_excerpt: "",
        post_status: "publish",
        comment_status: "open",
        ping_status: "open",
        post_password: "",
        post_name: "Sample Name",
        to_ping: "",
        pinged: "",
        post_modified: "2019-03-01 16:35:58",
        post_modified_gmt: "2019-03-01 16:35:58",
        post_content_filtered: "",
        post_parent: 0,
        guid: "[url]",
        menu_order: 0,
        post_type: "post",
        post_mime_type: "",
        comment_count: "0",
        filter: "raw",
    },
    2: {
        ID: 2,
        post_author: "1",
        post_date: "2019-02-26 15:50:52",
        post_date_gmt: "2019-02-26 15:50:52",
        post_content: "Sample Content",
        post_title: "Sample Title",
        post_excerpt: "",
        post_status: "publish",
        comment_status: "open",
        ping_status: "open",
        post_password: "",
        post_name: "Sample Name",
        to_ping: "",
        pinged: "",
        post_modified: "2019-03-01 16:35:58",
        post_modified_gmt: "2019-03-01 16:35:58",
        post_content_filtered: "",
        post_parent: 0,
        guid: "[url]",
        menu_order: 0,
        post_type: "post",
        post_mime_type: "",
        comment_count: "0",
        filter: "raw",
    }
}
```

#### 3. Status
/wp-json/ff/v1/status
>This endpoint will return the latest modified date of any posts that will be returned through the Content endpoint. This is helpful when you store the data locally and need to check if data has been updated since your last fetch from the endpoints.
```
{
    last_update: 1551826384
}
```

## Usage
- When creating or editing a category for posts you will now be presented with an "Add to api" checkbox. When checked, The category will be fetchable from the above endpoints. 
