# Kirby Plugin: Listsubpages

By using the *KirbyTag* **`listsubpages`**, a *dynamically* generated **links list** with clickable *links to the "subpages" of the respective page* (or a parent page specified in the KirbyTag) *and a freely selectable heading* (e.g., "Select the desired page:") can be inserted into any *textarea* field in the panel.

The links list can be displayed in reverse order, without its own page, with its own page as text instead of as a link.

Drafts, unlisted and/or listed child pages can be included in the list; however, the draft and unlisted subpages are **only visible** if the website visitor is logged in!

If there are no subpages, **NOTHING** is displayed in the frontend.
For this reason, the heading (e.g. "Select the desired page:") is also displayed by the *KirbyTag*&nbsp;**`listsubpages`** (or not, if no pages are listed).


### A basic example:

This basic example uses a *hypothetical* webpage, which has three subpages.

Insert a table of contents for all *visible* "subpages" that are formatted as links in a *textarea* field in the panel

```html
(listsubpages: )
```

This generates the following HTML code in the front end:

```html
<h3 class="listsubpages">Select the desired page:</h3>
<ul class="listsubpages">
  <li><a href="http://yourdomain.com/this_page/daughter1">Subpage 1</a></li>
  <li><a href="http://yourdomain.com/this_page/daughter2">Subpage 2</a></li>
  <li><a href="http://yourdomain.com/this_page/daughter3">Subpage 3</a></li>
</ul>
```

and then **this is visible in the frontend**:

><h3>Select the desired page:</h3>
><ul>
><li><a href="#">Subpage 1</a></li>
><li><a href="#">Subpage 2</a></li>
><li><a href="#">Subpage 3</a></li>
></ul>


## Installation

### Download

[Download](https://github.com/HeinerEF/kirby-listsubpages/archive/master.zip) the contents of this repository as Zip file.

Rename the **extracted** folder to `heineref_listsubpages` and copy it into the `site/plugins/` directory in your Kirby project. If it does not exist, create a new directory `site/plugins/` first.
This file `README.md` therefore receives the path `site/plugins/heineref_listsubpages/README.md`.

### Composer

```html
composer require HeinerEF/kirby-listsubpages
```

### Git submodule

If you have used git in your project before:

```html
git submodule add https://github.com/HeinerEF/kirby-listsubpages.git site/plugins/heineref_listsubpages
```


## Setup

### Other translations

Put your translations files to other languages (must be php files) into the Plugin subfolder `./translations`.

That's it, **no** configuration needed.

## Options

All options require **`HeinerEF.listsubpages.`** as prefix.

**`listtag`**

- default: ul
- the tag used for the list of the subpage links:
    * ul = bullet point (unordered list) - the default
    * ol = numbered list (ordered list)


**`showstatuses`**

- default: TRUE
- show the status of the page if it is not 'listed'
- however, the draft and unlisted subpages are **only visible** if the website visitor is logged in!


## Using the *KirbyTag*

Display the table of contents (list of the subpages) on a webpage

```html
(listsubpages: )
```


Display a table of contents (list of the subpages) with the heading "*Select another page:*"

```html
(listsubpages: Select another page:)
```

**Note:** Place the heading direct after "*listsubpages:*" in front of all other KirbyTag attributes!


Display the table of contents (list of the subpages) of the **blog** page, which has the path "/blog", e.g. on a blogarticle page:

```html
(listsubpages: parentdir: blog)
```


Display the table of contents (list of the subpages) of the **blog** page, which has the path "/blog", e.g. on a blogarticle page, without the current page

```html
(listsubpages: listself: no parentdir: blog)
```


Display the table of contents (list of the subpages) of the **blog** page, which has the path "/blog", e.g. on a blogarticle page, showing the current page as text rather than a link

```html
(listsubpages: listself: text parentdir: blog)
```


Display the table of contents (subpages) in reverse order

```html
(listsubpages: flip: yes)
```


Display the table of contents (subpages) as a numbered list

```html
(listsubpages: listtag: ol)
```

### "children"-options:

In Kirby, pages can have three states:

- draft
- unlisted
- listed

Drafts are only accessible to logged-in users, while listed and unlisted pages are accessible via their URL.

However, these differences are **only visible** if the website visitor is logged in!


|   children   | draft | unlisted | listed |  comment  |
|:------------:|:-----:|:--------:|:------:|-----------|
|              |       |    x     |   x    | (default) |
|     all      |   x   |    x     |   x    |           |
|  onlylisted  |       |          |   x    |           |
| onlyunlisted |       |    x     |        |           |
|  onlydraft   |   x   |          |        |           |
|  notlisted   |   x   |    x     |        |           |
| notunlisted  |   x   |          |   x    |           |

Output a table of contents for *all* child pages (drafts, public and unlisted), but drafts only for logged in Kirby user

```html
(listsubpages: children: all)
```


Output a table of contents for *listed* child pages

```html
(listsubpages: children: onlylisted)
```


Output a table of contents for *unlisted* child pages

```html
(listsubpages: children: onlyunlisted)
```


Output a table of contents for *drafts* child pages

```html
(listsubpages: children: onlydraft)
```


Output a table of contents for *drafts and unlisted* child pages

```html
(listsubpages: children: notlisted)
```


Output a table of contents for *drafts and listed* child pages

```html
(listsubpages: children: notunlisted)
```

###  Other options:

Display a table of contents **without** the statuses of subpages (otherwise, this is displayed only for logged in Kirby user)

```html
(listsubpages: nostatuses: yes)
```

Otherwise, the `option(‘HeinerEF.listsubpages.showstatuses’)` or the default TRUE are taken into account.


Output a table of contents with the CSS class "*testclass*" instead of the default class "*listsubpages*" (for details look at the "basic example")

```html
(listsubpages: class: testclass)
```


Output a specially formatted table of contents

```html
(listsubpages: css: "color: blue;  list-style-type: square; font-weight: bold;")
```

## Requirements

This plugin was built using **Kirby 3.x** and updated up to **Kirby 5.x**.
It does not work as is with older versions of Kirby.

## Disclaimer

This plugin is provided "**as is**" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment.

## License

[MIT](LICENSE.md)

It is not permitted to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any form of hate speech.

## Credits

- [Bastian Allgeier](https://github.com/bastianallgeier) for Kirby and its KirbyTags
