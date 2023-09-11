# Polylang Open
- Requires at least: WordPress: 4.0.0, Polylang: 1.7
- Tested up to: 5.7.0
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html
- Tags: polylang, single-slug, rest-api
- Contributors: Anton Peetso(@antonpeetso), grapplerulrich, Marc-Antoine Ruel

## TLDR
Was too cheap to get Polyang PRO so put together a plugin instead. Hopefully I will maintain this, hopefully...

# Features
* Allows same slug for multiple languages in Polylang.
* Adds "polylang_lang" and "polylang_translations" to WP-JSON(posts).

# Usage
## In Gutenberg-block:
When using getEntityRecords, you'll be able to pass the "polylang_lang" and it's language slug to retrieve posts with that language slug.
````
export default compose([
	withSelect((select, props) => {
		const {attributes} = props;
		const {getEntityRecords} = select('core');

		return {
			pages: getEntityRecords('postType', 'page', {per_page: -1, polylang_lang: "da"}),
			languages: getEntityRecords('polylang-open/v1', 'languages'),
		};
	}),
	withInstanceId,
])(MyComponent);
````

## Using WP-JSON
### Routes
* /wp-json/polylang-open/v1/languages
* /wp-json/wp/v2/pages?polylang_lang=sv

### Core
When using this plugin the WP-JSON will accept the parameter "polylang_lang" which is used to retrieve posts where language.
<br/>
<br/>
For Example:
````
/wp-json/wp/v2/pages?polylang_lang=sv
/wp-json/wp/v2/pages?polylang_lang=da
/wp-json/wp/v2/pages?polylang_lang=en
````

### Language
The language slug value of the post
```
{
  [...]
  "polylang_lang": "en"
  [...]
}
```

### Translations
List of translation for the post
```
{
  [...]
  "polylang_translations": [
    {
      "slug": "sv",
      "id": 1
    }
  ],
  [...]
}
```

## Create posts with same slug through wp_insert_post
````
$sv_post_id = wp_insert_post(array(
    'post_status'  => 'publish',
    'post_name'  => "SAME-SLUG",
    'post_title'  => "SV TITLE",
    'content'      => '',
));
pll_set_post_language($sv_post_id, 'sv');

// The slug for this post will not be the same as the one above.
$en_post_id = wp_insert_post(array(
    'post_status'  => 'publish',
    'post_name'  => "SAME-SLUG",
    'post_title'  => "EN TITLE",
    'content'      => '',
));
pll_set_post_language($en_post_id, 'en');
pll_save_post_translations(array('sv' => $sv_post_id, 'en' => $en_post_id));

// Here the slug will actually be updated.
wp_update_post(array(
    'ID' => $sv_post_id,
    'post_name' => "SAME-SLUG",
));
wp_update_post(array(
    'ID' => $en_post_id,
    'post_name' => "SAME-SLUG",
));
````
