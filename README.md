# Avatar-from-ACF
A lightweight WordPress plugin that replaces the default Gravatar/avatar output with a user-uploaded image stored in an ACF avatar image field. Supports both core get_avatar() and get_avatar_url() filters, and ensures compatibility with Elementor’s Author Image widget (handles post and dynamic tag objects)


Installation

Clone or download this repository into your wp-content/plugins/ directory:

git clone https://github.com/noleemits/Avatar-from-ACF

Rename the folder if desired (e.g., acf-user-avatar).

Activate the plugin in WordPress: Plugins → Installed Plugins → Activate “ACF User Avatar”.

Configuration

In WordPress admin, go to Custom Fields → Add New and create a field group for Users.

Add a field with the following settings:

Field Label: Avatar

Field Name: avatar

Field Type: Image

Return Value: Image ID

Set Location rule to User Form is All (or restrict to specific roles).

Save the field group.

Usage

Edit any user profile (Users → Your Profile) and upload an image into the “Avatar” field.

Anywhere WordPress calls get_avatar() or get_avatar_url(), your ACF image will display at the requested size.

Elementor’s Author Image widget will now show your custom ACF avatar instead of the default.

Customization

Field NameIf you change the ACF field name, update the plugin’s PHP:

$image_id = get_field('your_field_name', 'user_' . $user->ID);

URL-only ReplacementTo replace just the URL, modify the get_avatar_url filter to return wp_get_attachment_image_url( $image_id, $size ).

Requirements

WordPress 5.0+

Advanced Custom Fields (free or Pro)
