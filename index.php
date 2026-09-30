<?php
      // \site\plugins\heineref_listsubpages\index.php

      // last update 30.09.2026 by HeinerEF some updates
      // update      01.08.2026 by HeinerEF some updates
      //             26.07.2026 by HeinerEF add some new options
      //             31.05.2026 by HeinerEF update README.md and here
      //             24.05.2026 by HeinerEF update README.md
      //             12.05.2026 by HeinerEF add language setup for not multilang-site
      //             12.10.2025 by HeinerEF first steps for multilanguage
      //             24.04.2025 by HeinerEF ($subpage->uri() == page()->uri()) for K5
      //             30.07.2023 by HeinerEF Option: listself: no and text
      //             08.03.2023 by HeinerEF Option: parentdir
      //             29.12.2022 by HeinerEF "r(($subpage->pagetitle() == ''), $subpage->title(), $subpage->pagetitle())" is visible text
      //             25.12.2021 by HeinerEF Options: notunlisted, nostatuses
      //             02.01.2021 by HeinerEF Option: flip [returns the elements in reverse order]
      //             23.05.2020 by HeinerEF update: role() != 'fishermen'
      //             21.03.2020 by HeinerEF update for Kirby 3.3.5
      //             27.10.2019 by HeinerEF update for Kirby 3.3.0-rc2
      //             23.03.2019 by HeinerEF K3-plugin: \site\plugins\heineref_listsubpages\index.php
      //             09.01.2017 by HeinerEF K2:        \site\tags\listsubpages.php

/* dump translations at the top of the webpage for development purposes:
var_dump(
  A::keyBy(A::map(
    Dir::read(__DIR__ . '/translations'),
      fn($file) => A::merge(['lang' => F::name($file),], Yaml::decode(Data::read(__DIR__ . '/translations/' . $file)))
                 ), 'lang')
        );
/* */

use Kirby\Toolkit\Locale;

Kirby::plugin('heineref/listsubpages', [

  // get the translations from all language files (must be php files) in "./translations"
  'translations' => A::keyBy(A::map(
    Dir::read(__DIR__ . '/translations'),
      fn($file) => A::merge(['lang' => F::name($file),], Yaml::decode(Data::read(__DIR__ . '/translations/' . $file)))
                                   ), 'lang'),
  'tags' => [
    'listsubpages' => [
      'attr' => array(
        'text',       // $title field
        'class',      // 'listsubpages' (default), ...
        'children',   // 'listed' (default), 'unlisted', 'drafts', ... = 'all'
        'css',        // has to include the TWO quotation marks ( 2x '"' )
        'flip',       // returns the elements in reverse order
        'nostatuses', // NO status of the subpages
        'listtag',    // 'ol' = numbered list, 'ul' = bullet point (unordered list)
        'parentdir',  // parentdir
        'listself',   // listself
      ),
      'html' => function($tag) {
        $text       = $tag->attr('text');
        $class      = $tag->attr('class', 'listsubpages');
        $children   = $tag->attr('children');
        $css        = $tag->attr('css');
        $flip       = $tag->attr('flip');
        $listself   = $tag->attr('listself');
        $nostatuses = $tag->attr('nostatuses');
        $listtag    = $tag->attr('listtag', 'ul');
        $parentdir  = page($tag->attr('parentdir'));

        $kirby = kirby();

        IF (!$parentdir) {
          $parentdir = $tag->parent();
        };

        /* "children"-options:

        |   children   | draft | unlisted | listed |  comment  |
        |--------------|-------|----------|--------|-----------|
        |              |       |    x     |   x    | (default) |
        |     all      |   x   |    x     |   x    |           |
        |  onlydraft   |   x   |          |        |           |
        | onlyunlisted |       |    x     |        |           |
        |  onlylisted  |       |          |   x    |           |
        |  notlisted   |   x   |    x     |        |           |
        | notunlisted  |   x   |          |   x    |           |

        However, drafts and unlisted pages are **only visible** if the visitor of the webpage with the KirbyTag `listsubpages` is logged in!

        */

        $subpages = $parentdir->children(); // default: gets no drafts but the rest
        if      ($children == 'all') {
          $subpages = $parentdir->childrenAndDrafts();
        } elseif($children == 'onlydraft') {
          $subpages = $parentdir->drafts();
        } elseif($children == 'onlyunlisted') {
          $subpages = $subpages->unlisted();
        } elseif($children == 'onlylisted') {
          $subpages = $subpages->listed();
        } elseif($children == 'notlisted') {
          $subpages = $parentdir->childrenAndDrafts()->not($parentdir->children()->listed());
        } elseif($children == 'notunlisted') {
          $subpages = $parentdir->childrenAndDrafts()->not($parentdir->children()->unlisted());
        };

        /* :"children"-options */

        if(!($user = $kirby->user())) { // show invisible pages ONLY for kirby-users
          $subpages = $subpages->listed();
        };

        if($listself == 'no') {          // add " listself: no" in the content file
          $subpages = $subpages->not(page());
        };

        if($flip) {                      // add " flip: yes" in the content file
          $subpages = $subpages->flip(); // returns the elements in reverse order
        };

        $showstatuses = option('HeinerEF.listsubpages.showstatuses', TRUE);
        if($nostatuses) {
          $showstatuses = FALSE;
        };

        // ol = numbered list, ul = bullet point (unordered list)
        $listtag = option('HeinerEF.listsubpages.listtag', $listtag);


        if($subpages->count() > 0) {

          if($kirby->multilang()) :
            $mylocale = $kirby->languageCode();
          else:
            if(!option('locale')) :
              $mylocale = 'en';          // default
            else:
              $mylocale = r((strlen(option('locale')[LC_ALL]) > 1), option('locale')[LC_ALL], option('locale'));
            endif;
            $mylocale = substr($mylocale, 0, 2);
          endif;

          if($tag->attr('listsubpages')) {
            $title = $tag->attr('listsubpages');
          } else {
            $title = t('HeinerEF.listsubpages.title', 'Select the desired page:', $mylocale);
          };

          $html  = '<h3 class="' . $class . '">' . $title . '</h3>' . "\n";
          $html .= '<' . $listtag . ' class="' . $class . '"' . r($css <> '', ' style=' . $css . '') .'>' . "\n";

          foreach($subpages as $subpage) {
            $html .= '  <li>';
            $mytitle = kirbytextinline(str::unhtml(r(($subpage->pagetitle() == ''), $subpage->title(), $subpage->pagetitle())));
            if(($listself == 'text') AND ($subpage->uri() == page()->uri())) { // add " listself: text" in the content file
              $html .= $mytitle;
            } else {
              $html .= '<a title="' . $mytitle . '" href="' . $subpage->url() . '">' . $mytitle . '</a>';
            };
            if( $showstatuses AND ($subpage->status() <> 'listed') ):
              $html .= ' <span class="mini">&nbsp;<code class="pagestatus" title="' . t('HeinerEF.listsubpages.statustitle') . '">(' . I18n::translate("page.status." . $subpage->status(), $subpage->status(), $mylocale) . ')</code></span>';
            endif;
            $html .= '</li>' . "\n";
          }

          $html .= '</' . $listtag . '>' . "\n";

        } else {

          $html  = '';  // no subpages found!

        }

        return $html;

      }
    ]
  ]
]);
