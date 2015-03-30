#
# Automatically generated: Please do not change (generate path sections)
#

# Require any additional compass plugins here.

cache_dir = "cache/.sass-cache"

# sass path
sass_dir = "../src/XRealm/AppBundle/Resources/sass"

# some directories
css_dir = "../web/styles/"
images_dir = "../web/"
javascripts_dir = "../web/js/"

# prefered syntax
preferred_syntax = :scss

add_import_path "../vendor/twbs/bootstrap-sass/assets/stylesheets"

# keep line comments for development
line_comments = (environment == :production) ? false : true;

# define output style depending on environment
output_style = (environment == :production) ? :compressed : :expanded

# set relative paths for development (will disable CDN)
#relative_assets = (environment == :production) ? false : true
relative_assets = true

# define HTTP image path
#http_images_path = "/content/faktura/web/"

# use CDN for assets
#asset_host do |asset|
#	"//cdn.new-data-services.de" % (asset.hash % 4)
#end;

# disable cache buster
asset_cache_buster do |http_path, real_path|
	nil
end