# Changelog

## Version 2.4.12 - December 2, 2025

- Fixed UI dynamic related error

## Version 2.4.11 - November 25, 2025

- Added Request Source referer field mapping
- Fixed PHP 8.4 deprecation warnings

## Version 2.4.10 - November 4, 2025

- Added Download ID Key support (Joomla)

## Version 2.4.9 - October 23, 2025

- Fixed Joomla 6 compatibility
- Fixed PHP 8.4 deprecation warnings

## Version 2.4.8 - October 20, 2025

- Fixed LinkedIn API deprecation notice

## Version 2.4.7 - September 15, 2025

- Fixed Cloudflare authentication
- Fixed config encryption workflow (WordPress)
- Fixed UI regression affecting Global Queries
- Fixed issues with external database connections in Database actions

## Version 2.4.6 - September 2, 2025

- Fixed webhook action
- Fixed issue when connecting to Google oAuth for some users

## Version 2.4.5 - August 25, 2025

### New Features

- Added support for mapping single images from LinkedIn source post

### Bug Fixes

- Fixed Cloudflare API PHP warning
- Fixed Changelog display regression
- Fixed issue fetching LinkedIn source organization data
- Fixed parent inheriting support for query dynamic arguments

## Version 2.4.4 - July 30, 2025

- Fixed PHP warnings
- Fixed dynamic parent resolving edge case
- Fixed YouTube and TikTok API regressions
- Fixed failing updates on some wordpress installations

## Version 2.4.3 - July 21, 2025

### New Features

- Added subscriber language field in AcyMailing action

### Bug Fixes

- Fixed Dynamic UI related issues
- Fixed Facebook auth new tokens validation

## Version 2.4.2 - July 10, 2025

- Fixed config init

## Version 2.4.1 - July 10, 2025

- Fixed build checksum affecting new installations

## Version 2.4.0 - July 9, 2025

### New Features

- Added composed source conditions 🥳
- Added composed source nested compositions
- Added Facebook Page Events source query
- Added form fields dynamic control name and attributes
- Added form database actions multi-record matching
- Added TikTok source video reach-text parsing
- Added Twitter source tweet reach-text parsing
- Added LinkedIn source post reach-text parsing

### Bug Fixes

- Fixed Twitter source tweet images/videos schema
- Fixed LinkedIn source post date fields resolving
- Fixed LinkedIn source posts start argument default
- Fixed Friendly Captcha widget accessibility issue
- Fixed argument references in form Message action hooks
- Fixed form upload field required attr not being applied

### Changes

- Refactored config encryption workflow
- Optimized Twitter source to reduce API quota usage
- Improved accessibility for form radio and checkbox fields
- Deprecated the creation of form areas in sections and/or columns

## Version 2.3.12 - April 23, 2025

### New Features

- Added option to transform a section/column Form Area into a Sublayout

### Bug Fixes

- Fixed Bluesky Source post URI resolution
- Fixed form Date/Time input min and max attributes not being applied

## Version 2.3.11 - April 15, 2025

### New Features

- Added Bluesky Source posts RichText support

### Bug Fixes

- Fixed Social Sharing WhatsApp and Viber defaults

## Version 2.3.10 - April 2, 2025

### New Features

- Added Google reCaptcha language settings
- Added Bluesky and Mastodon networks to Social Sharing element

### Bug Fixes

- Fixed dynamic condition evaluating numerical values
- Fixed dynamic resolving for inherited parent fields with different arguments

### Changes

- Updated Phosphor Icon Collection to 2.1.1
- Renamed Social Sharing element Twitter network as X

## Version 2.3.9 - March 17, 2025

- Fixed form submit hook data access and submit prevention
- Fixed issue with some SFTP connections not working properly
- Fixed dynamic preserve words filter missing as option for limit filter
- Fixed dynamic upgrade script not considering deep nested query conditions
- Fixed options not display when text is falsy value in form select, checkbox and radio elements

## Version 2.3.8 - February 25, 2025

### Bug Fixes

- Fixed Chart tooltip callback regression
- Fixed IP Geolocation condition regression

### Changes

- Refactored Global Queries section

## Version 2.3.7 - February 21, 2025

### New Features

- Added field-change form hook

### Bug Fixes

- Fixed ui minor issues
- Fixed dynamic query args resolving when inheriting parent

## Version 2.3.6 - February 19, 2025

### Bug Fixes

- Fixed ui general issues
- Fixed dynamic resolving regression

## Version 2.3.5 - February 18, 2025

### Bug Fixes

- Fixed ui issue affecting auths editing
- Fixed Google Business Profile source migration
- Fixed dynamic resolving when third-parties involved

## Version 2.3.4 - February 17, 2025

### Bug Fixes

- Fixed global queries ui regresion
- Fixed Layout Debug support on templates
- Fixed Social Sharing element and XML source migration script

## Version 2.3.3 - February 14, 2025

### Bug Fixes

- Fixed dynamic ui regression affecting parent inheritance

## Version 2.3.2 - February 14, 2025

### Bug Fixes

- Fixed Social Sharing element grid
- Fixed Global Queries node assignment
- Fixed migration scripts affecting sources

## Version 2.3.1 - February 13, 2025

### Bug Fixes

- Fixed Phosphor Icon Collection build
- Fixed ui issue affecting dynamic fields

### Changes

- Removed composed sources preview highlighting (until better approach)

## Version 2.3.0 - February 13, 2025

### New Features

- Added Bluesky source
- Added LinkedIn source
- Added Mailchimp Audience source
- Added Webhook action
- Added CSV Delete Record action
- Added Database Delete Record action
- Added Google Sheets Delete Record action
- Added CoreUI icon collection
- Added Phosphor icon collection
- Added Pixelarticons icon collection
- Added User Group condition rule (WordPress)
- Added XML source long lived cache
- Added status toggle setting for all sources
- Added Facebook Post Attachments multi-item source content
- Added Facebook Page Posts query Type Argument allowing to choose what posts to fetch
- Added custom label option for composed sources
- Added preview highlight when hovering composed sources
- Added multiple items mapping for field and composed sources
- Added Form Area attributes settings
- Added Fieldset form element Sublayouts support
- Added Message form action after show/hide custom scripts
- Added better support for third-party form elements
- Added Dynamic condition rule Case Insensitive setting
- Added Social Sharing element custom SVG icons
- Added Social Sharing element custom shared URL
- Added Chart element item.metadata shortcut for tooltip callbacks
- Added opt-in Layout Debug with layout source real-time view

### Changes

- Updated Chart.js to v4.4.7
- YOOtheme Pro 4.5 minimum supported version
- Refactored dynamic workflow (ui and resolving)
- Refactored Mailchimp action interests mapping
- Refactored SaveToDatabase form action as Database Upsert Record action
- Refactored SaveToGoogleSheet form action as Google Sheets Upsert Record action
- When composed source applied will inherit static content present in the field
- Condition rule User Group (Joomla) no longer retrieves the user group with inheritance
- Refactored Access Condition and Query Filters panel buttons showing amount of rules
- Unresolved composed sources placeholders will be hidden from output unless in edit mode
- Removed Teenyicons and Zondicons Icon Collections
- Updated Bootstrap, Font Awesome, Heroicons, Octicons, Remix Icon and Tabler Icons Icon Collections

## Version 2.2.32 - February 3, 2025

- Fixed PHP warning affecting WordPress with WPML
- Fixed Instagram and other sources authentication

## Version 2.2.31 - December 17, 2024

### Bug Fixes

- Fixed Instagram source scopes

## Version 2.2.30 - December 16, 2024

### Bug Fixes

- Fixed timezone error on Calendar condition rules

## Version 2.2.29 - December 12, 2024

### New Features

- Added WPML support for Access Language Rule

### Bug Fixes

- Fixed timezone evaluation on Time and Datetime condition rules
- Fixed Instagram Media total comments and likes missing field mapping

## Version 2.2.28 - December 4, 2024

### Bug Fixes

- Fixed form actions loading
- Fixed Instagram auth php error

## Version 2.2.27 - December 3, 2024

### New Features

- Added new Instagram Login for Business accounts

### Bug Fixes

- Fixed UI regression (yet annother)
- Fixed Dynamic Rule comparison of numeric values

### Changes

- Removed the now deprecated Instagram Personal source and auth

## Version 2.2.26 - November 21, 2024

### Bug Fixes

- Fixed UI regression

## Version 2.2.25 - November 14, 2024

### Bug Fixes

- Fixed Geolocation access rule logs
- Fixed YOOtheme Pro 4.5 compatibility

## Version 2.2.24 - October 31, 2024

### Bug Fixes

- Fixed assets loading (Joomla)
- Fixed form redirect action to external urls (WordPress)

## Version 2.2.23 - October 24, 2024

- Fixed minor UI issues
- Fixed form redirect action not working when used with composable sources

## Version 2.2.22 - October 11, 2024

- Fixed regression

## Version 2.2.21 - October 10, 2024

- Added YOOtheme Pro 4.5 compatibility

## Version 2.2.20 - October 7, 2024

### Bug Fixes

- Fixed empty xml source issue
- Fixed global queries duplicates
- Fixed Google Business Profile after midnight open hours

## Version 2.2.19 - September 17, 2024

### Bug Fixes

- Fixed an issue with XML and RSS Source caching
- Fixed image caching when asset has no extension
- Fixed SaveToDB action error if row is updated without changes

## Version 2.2.18 - September 3, 2024

### Bug Fixes

- Fixed form validation regression
- Fixed Instagram Personal source pagination

## Version 2.2.17 - August 28, 2024

### Bug Fixes

- Fixed form fields validation when empty values
- Fixed Validate Action when used with custom query mode

## Version 2.2.16 - August 13, 2024

### Bug Fixes

- Fixed form checkbox validation
- Fixed RSS source issue with itunes tags
- Fixed Airtable source multiple fields mapping

## Version 2.2.15 - July 12, 2024

### New Features

- Added query filters, Starts/Ends Not With, for Database and CSV sources

### Bug Fixes

- Fixed RSS and XML cache issues
- Fixed form checkbox html5 validation
- Fixed AcyMailing lists paginaton limit
- Fixed YouTube source queries with dates filtering
- Fixed saving of AWS and Cloudflare auth credentials
- Fixed saving of module layouts on custom layout libraries
- Fixed issues when using custom content directory (WordPress)
- Fixed database source eager loading when used in replication context

### Changes

- Changed GeoIP default provider from Akeeba to Regular Labs (Joomla)
- Google Calendar source events displays accordingly to the site tz instead of calendar's

## Version 2.2.14 - May 31, 2024

### Bug Fixes

- Fixed support for Google API keys auth
- Fixed support for Facebook custom app auth

## Version 2.2.13 - May 23, 2024

### Bug Fixes

- Fixed compatibility with YOOtheme Pro 4.4
- Fixed support for custom Facebook Apps authentication

## Version 2.2.12 - May 21, 2024

### Bug Fixes

- Fixed config assets duplication
- Fixed Social Sharing mail icon fallback
- Fixed source queries arguments reactivity

### Changes

- Form Select, Radio and Checkbox values do not fallback to text anymore, but the opposite

## Version 2.2.11 - May 8, 2024

- Fixed form fieldset regression

## Version 2.2.10 - May 8, 2024

### Bug Fixes

- Fixed database auth migration
- Fixed Storage creation regression
- Fixed fatal error when a Database source table doesn't exist
- Fixed form option fields validation and support for a zero value

### Changes

- Email cloaking will be disabled only when an Email input field present in a form
- Google Business Profile location time periods are properly merged now plus support More Hours
- Form email action will warn against using From Email setting (use reply-to instead)

## Version 2.2.9 - April 18, 2024

### Bug Fixes

- Fixed regression

## Version 2.2.8 - April 18, 2024

### Bug Fixes

- Fixed UI issues affecting dynamic workflow
- Fixed value fallback for Radio and Checkbox options
- Fixed issue with XML source not rendering certain xml structures
- Fixed Google Business Profile source edge display issue with special hours
- Fixed forms custom scripts execution when the form is rendered in a modal

### Changes

- A Sublayout element is now a valid form area
- Updated TikTok integration for compatibility with their API v2
- Enforced single events for Google Calendar source events query

## Version 2.2.7 - March 14, 2024

### Bug Fixes

- Fixed several UI issues concerning dynamic content
- Fixed PHP warning when creating records with Airtable action
- Fixed User Level condition when evaluating a guest role (WordPress)
- Fixed Instagram Business source config fetching large list of pages

## Version 2.2.6 - February 29, 2024

### Bug Fixes

- Fixed form regression
- Fixed AcyMailing integration edge issues
- Fixed resolving global queries with multi sources and dynamic arguments

## Version 2.2.5 - February 22, 2024

### New Features

- Added AcyMailing after submit actions
- Added XML File and XML Stream sources
- Added Cloudflare Turnstile CAPTCHA alternative
- Added action conditions to Mailchimp and Validation actions

### Bug Fixes

- Fixed RSS source caching
- Fixed Mailchimp action birthday merge field mapping
- Fixed Google Business Profile special hours display
- Fixed Google Calendar API default event types

## Version 2.2.4 - January 30, 2024

### New Features

- Added Airtable record User based fields mapping
- Added Airtable record single attachment mapping in addition to all

### Bug Fixes

- Fixed DB related PHP warning (Joomla)

## Version 2.2.3 - January 29, 2024

### New Features

- Added YouTube singular Video Query
- Added YouTube video ID argument for Video and Videos queries
- Added Airtable record attachment and multi value source fields

### Bug Fixes

- Fixed Google Calendar all day events fields mapping
- Fixed PHP type warnings affecting YouTube and other sources

## Version 2.2.2 - January 26, 2024

### Bug Fixes

- Fixed DB related issues (WordPress)
- Fixed Google Calendar events query when filtered by single events

## Version 2.2.1 - January 26, 2024

### Bug Fixes

- Fixed circular recursion error
- Fixed SEF support when routing

## Version 2.2.0 - January 24, 2024

### New Features

- Added Dynamic Query Arguments 🥳
- Added Google Photos source
- Added Google Calendar source
- Added Mailchimp actions
- Added Airtable source and actions
- Added datemodify source filter globally

## Version 2.1.6 - January 22, 2024

### Bug Fixes

- Fixed RSS Source feed issue
- Fixed sources related PHP error
- Fixed support for imploding multi value sources
- Fixed resolving dynamic filters in composed sources

## Version 2.1.5 - January 12, 2024

### Bug Fixes

- Fixed Chart element render regresion

## Version 2.1.4 - January 10, 2024

### New Features

- Added optional metadata field to Chart element data

### Bug Fixes

- Fixed listing sources grouped as Global

## Version 2.1.3 - November 24, 2023

### Bug Fixes

- Fixed Database connection regression

## Version 2.1.2 - November 23, 2023

### Bug Fixes

- Fixed icons rendering regression

### Changes

- An unresolved composed source will no longer trigger a PHP warning

## Version 2.1.1 - November 22, 2023

### New Features

- Added support to vertically resize a composed source editor

### Bug Fixes

- Fixed Database connections
- Fixed loading myicons collection
- Fixed resolving composed sources arguments
- Fixed UI issue affecting Global Queries and Dynamic Options

## Version 2.1.0 - November 16, 2023

### New Features

- Added Composable Sources 🥳
- Added Joomla 5 compatibility
- Added YOOtheme Pro 4.2 compatibility
- Added Database Auth Driver
- Added Customizer access condition rule
- Added attribute settings to form elements

### Bug Fixes

- Fixed Instagram Source pagination
- Fixed Twitter Source My Tweets query
- Fixed custom oauth apps for Google / YouTube
- Fixed cache time settings for custom oAuth apps
- Fixed form options being ignored during an element transform
- Fixed Related Articles count (Joomla)
- Fixed support for custom Icon Collections
- Fixed access conditions dynamic resolving when mapping new fields
- Fixed User condition rule resolving

### Changes

- Reviewed form elements settings configuration
- Database Sources custom connections migrated to Database Auth

## Version 2.0.19 - October 26, 2023

### New Features

- Added support for multiple instances of form Download action
- Added control name as meta data to the form source fields list

### Bug Fixes

- Fixed displaying form validation errors even when control output is missing

### Changes

- Changed Honeypot validation default message
- Google Business Profile average rating rounded by default

## Version 2.0.18 - October 17, 2023

### New Features

- Added Forms Hidden Field advanced tab settings ID, Name, Status, and Transform

### Bug Fixes

- Fixed platform configuration mapping (joomla)
- Fixed db source filtering when used in a condition rule
- Fixed Save to Database form action updating empty values that where not set.

### Changes

- The Message Form Action does not reset the form anymore, there is a form setting instead

## Version 2.0.17 - October 6, 2023

### New Features

- Added YOOtheme Pro 4.1 compatibility

### Bug Fixes

- Fixed icon loading in 404 templates

## Version 2.0.16 - September 28, 2023

### Bug Fixes

- Fixed source dynamic fields encoding

## Version 2.0.15 - September 27, 2023

### Bug Fixes

- Fixed errors log (WordPress)
- Fixed config saving issue (WordPress)
- Fixed YouTube source thumbnail caching

## Version 2.0.14 - September 8, 2023

### New Features

- Added format settings for Chart tooltip and scale ticks
- Added week start day settings for Google Business Profile open hours query

### Bug Fixes

- Fixed authentication issue with Instagram personal accounts
- Fixed profile locations selection for Google Business Profile source

## Version 2.0.13 - August 30, 2023

### New Features

- Added SFTP storage as part of FTP collection
- Added forms setting to disable fields reset after submission
- Added Chart element scale type, min, max, reverse and ticks options

### Changes

- Updated icon collections

### Bug Fixes

- Fixed Twitter Auth renew token
- Fixed dynamic workflow edge issues
- Fixed menu icons loading while in the customizer
- Fixed Database Source issue if a table is missing
- Fixed layout libraries overriding 3rd party libraries

## Version 2.0.12 - July 13, 2023

### New Features

- Added Scroll Into View behaviour when a form field validation has failed

### Changes

- Disabled email cloaking in forms (Joomla)

### Bug Fixes

- Fixed issue with database source filtering
- Fixed resolving of disabled condition rules
- Fixed evaluation of Time condition rule involving midnight hours
- Fixed Google Business Profile open hours display when a period has past midnight hours

## Version 2.0.11 - July 4, 2023

### New Features

- Added Chart Animation, Padding, and Ratio settings
- Added Chart IndexAxis settings replacing HorizontalBar
- Added Chart per Axis settings (Display, Position, Stack, Title, Grid)
- Added Facebook Page Photos source query
- Added Facebook Page Reviews source query
- Added TikTok MyVideos Filter by ID setting

### Changes

- Removed Facebook Page Personal Info mapping option
- Removed option to authenticate with ZOOlanders oAuth on Twitter driver

### Bug Fixes

- Fixed Upload field restricting all files by default (Firefox)

## Version 2.0.10 - June 23, 2023

### New Features

- Added support for any form field to be set as an email action attachments source

### Bug Fixes

- Fixed support for mapping form fields in form actions
- Fixed Google Business Profile opening hours
- Fixed new auths saving issue

## Version 2.0.9 - June 20, 2023

### Bug Fixes

- Fixed resolving of condition rules props

## Version 2.0.8 - June 19, 2023

### New Features

- Added support for Header Social Icons
- Added transform and source fields to all elements
- Added support for inheriting closest queries on adjacent nodes

### Bug Fixes

- Fixed UI issues preventing the creation of new auths
- Fixed form actions errors affecting all actions in chain
- Fixed access evaluation logs not displaying (WordPress)
- Fixed form submission source persisting outside the form area

## Version 2.0.7 - June 6, 2023

### Bug Fixes

- Fixed ui issue related to repeatable fields not displaying properly
- Fixed pagination in YouTube Channel and Playlists queries
- Fixed warnings in Google Business Profile reviews query
- Fixed email action dynamic attachment field
- Fixed dynamic condition not showing eval field for less than evaluation

## Version 2.0.6 - May 31, 2023

### New Features

- Added pagination to Instagram Hashtagged Media query

### Bug Fixes

- Fixed cache time 0 support when used in templates with dynamic content
- Fixed access condition dynamic resolving issues

## Version 2.0.5 - May 30, 2023

### Bug Fixes

- Fixed layout libraries ui regressions
- Fixed dynamic fields resolving regressions
- Fixed dynamic button appearing multiple times
- Fixed support for Google Sheet sheets with custom names

## Version 2.0.4 - May 29, 2023

### Bug Fixes

- Fixed Access log reporting
- Fixed Chart element 'fill' line support
- Fixed auto-updater failing to perform a clean installation (WordPress)

## Version 2.0.3 - May 27, 2023

### New Features

- Added Chart element subtitle option

### Bug Fixes

- Fixed form validation regression
- Fixed Chart element display regression
- Fixed fetch config PHP warning (WordPress)
- Fixed core module loading PHP warning
- Fixed Instagram edge authentication issue
- Fixed support for legacy access rules
- Fixed Google Sheet api regression

## Version 2.0.2 - May 26, 2023

### Bug Fixes

- Fixed displaying graphql errors not related to essential sources
- Fixed PHP warning about form captcha elements id attribute generation

## Version 2.0.1 - May 25, 2023

### Bug Fixes

- Fixed js error affecting customizer UI
- Fixed issue with Database class in Joomla and Wordpress

## Version 2.0.0 - May 24, 2023

### New Features

- Added support for YOOtheme Pro 4
- Added Validate form action
- Added Database source Records Count query
- Added Articles Field by Regular Labs support (Joomla)
- Added error event for form HTML5 validations
- Added option to display form errors in a modal
- Added option to encrypt Hidden form field value
- Added center and stack modal options to Message form action
- Added custom js code form area settings for all form events
- Added cache setting on source instance level with option to override
- Added embed player mapping option to Vimeo source Video type
- Added embed player mapping option to YouTube source Video and Playlist types
- Added Channel, Channel Playlist and Channel Playlists queries to YouTube My Channel source
- Added Location Posts query to Google Business Profile source
- Added overall star rating mapping option to Facebook source Page type
- Added less than evaluation on Dynamic Access Rule

### Changes

- Removed support for YOOtheme Pro 2 and 3
- Removed addons dependencies on each other
- Removed Markdown element file setting in favor of File source
- Removed legacy access rules and form actions
- Refactored, optimized and reduced code base
- Renamed Google MyBusiness source as Google Business Profile
- Updated Chart's element library to v4.0.3
- Imposed min cache for sources interacting with APIs through ZOOlanders' Auth App

## Version 1.9.9 - May 9, 2023

### New Features

- Added limit filter to RSS source type
- Added Set as Null option to SaveToDatabase Forms Action
- Added Skip if Empty option to SaveToDatabase Forms Action

### Bug Fixes

- Fixed Carousel Albums thumbnail display in Instagram source

## Version 1.9.8 - April 4, 2023

### Bug Fixes

- Fixed auto-update regression (WordPress)

## Version 1.9.7 - April 3, 2023

### Bug Fixes

- Fixed Input Number form field min validation when set as 0
- Fixed channels support in auto-updates (WordPress)

## Version 1.9.6 - March 14, 2023

### Bug Fixes

- Fixed Checkbox and Radio form fields custom id support
- Fixed Form View Helper conflicting with 3rd party plugins
- Fixed RSS source issue with feeds containing list of strings
- Fixed Database source issue with relations having uppercase field names

## Version 1.9.5 - March 7, 2023

### Bug Fixes

- Fixed form source type fields name display
- Fixed form fields custom id attribute support
- Fixed Upload form field issue when using spaces in control name
- Fixed Chart Element color rendering regression
- Fixed Customizer UI Dropdown display regression

## Version 1.9.4 - February 27, 2023

### Bug Fixes

- Fixed Safari detection in Browser Access Condition rule
- Fixed Chart Element support for data entries custom colors
- Fixed Access conditions min version check in OS and Browser rules
- Fixed RSS feed source issue where entries had multiple repeated tags
- Fixed form submission edge issues by using JS fetch instead of UIkit ajax
- Fixed form submission data mapping of Upload/Select values when set as multiple

## Version 1.9.3 - January 25, 2023

### Bug Fixes

- Fixed time format used across Access and Forms addons
- Fixed form actions execution when conditioned with submission data

## Version 1.9.2 - January 13, 2023

### Bug Fixes

- Fixed Checkbox form field HTML5 validation when is not multiple
- Fixed Input form field datetime elements default value resolving to now
- Fixed Dynamic runtime performance when inheriting query
- Fixed Dynamic UI responsiveness when using custom query
- Fixed Dynamic UI compatibility issue with YTP 3.0.21

## Version 1.9.1 - December 22, 2022

### New Features

- Added target attr setting for Social Sharing MailTo

### Bug Fixes

- Fixed new sources image caching

## Version 1.9.0 - December 21, 2022

### New Features

- Added RSS source
- Added Twitter source
- Added Facebook source
- Added Instagram Business source query Date filters
- Added Instagram source Single Media Query and album media edge
- Added Vimeo source My Folder Videos query
- Added Vimeo source My Showcase Videos query
- Added attributes support to forms Option element (Checkbox, Radio, Select)

### Bug Fixes

- Fixed hCaptcha double validation with Checkbox type

## Version 1.8.14 - December 9, 2022

### Bug Fixes

- Fixed form style regression
- Fixed Input form field predefined value support

## Version 1.8.13 - December 1, 2022

### New Features

- Added local caching for Google MyBusiness source media

### Bug Fixes

- Fixed PHP warnings
- Fixed sources media caching
- Fixed forms accessibility issues
- Fixed Customizer UI performance issue

## Version 1.8.12 - November 15, 2022

### New Features

- Added FriendlyCaptcha form field support for EU endpoint
- Added Instagram source better pagination support

### Bug Fixes

- Fixed YTP 2.x UI regression
- Fixed form upload element width/size styling
- Fixed form upload element mimetype validation check (security vulnerability)
- Fixed form actions being executed even if disabled
- Fixed form saveTo actions data mapping if some columns disabled
- Fixed fatal error if a Layout Library storage is deleted

## Version 1.8.11 - November 10, 2022

### Bug Fixes

- Fixed Access date rules regression

## Version 1.8.10 - November 9, 2022

### Bug Fixes

- Fixed Access evaluation regression

## Version 1.8.9 - November 8, 2022

### New Features

- Added form Data action
- Added Access conditions custom logic evaluation and logs
- Added option to update an existing record with form Database action
- Added option to disable a field being mapped in SaveTo form actions

### Bug Fixes

- Fixed datetime picker manual date input
- Fixed Access time rule timezone evaluation
- Fixed double icons loading (Joomla)
- Fixed Database source relations (WordPress)

## Version 1.8.8 - October 26, 2022

### New Features

- Added support for form fields dynamic validation values
- Added Show Label control for form fields wrapped in a Fieldset
- Added support for min/max selection count validation on Checkbox & Select

### Changes

- Changed distribution of form elements settings
- Changed Instagram source into separate sources for Personal and Business accounts
- Changed YouTube source into separate sources for Channel and Playlist

### Bug Fixes

- Fixed Dynamic workflow regression
- Fixed plugin activation hook (WordPress)
- Fixed Download action file path resolving
- Fixed access conditions custom name display

## Version 1.8.7 - October 20, 2022

### Bug Fixes

- Fixed YTP 3.0.7 compatibility issue

## Version 1.8.6 - October 19, 2022

### Bug Fixes

- Fixed PHP 8 deprecation warnings
- Fixed 1.8 transition related issues
- Fixed theme version check (WordPress)
- Fixed Instagram source media local caching
- Fixed form node arguments overriding globals

## Version 1.8.5 - September 30, 2022

### New Features

- Added Page queries as dynamic option
- Added YouTube source query offset argument

### Bug Fixes

- Fixed config save regression (WordPress)

## Version 1.8.4 - September 27, 2022

### Bug Fixes

- Fixed YTP 3 compatibility issues

## Version 1.8.3 - September 26, 2022

### Bug Fixes

- Fixed global queries and other UI related issues
- Fixed support for multi value form elements during dynamic mapping

## Version 1.8.2 - September 20, 2022

### Bug Fixes

- Fixed customizer regressions
- Fixed saveToCSV action support for custom separator/enclosure

## Version 1.8.1 - September 17, 2022

### Bug Fixes

- Fixed saveTo actions update script
- Fixed SaveToDatabase action support for external databases

## Version 1.8.0 - September 14, 2022

### New Features

- Added SaveToDatabase after submit action
- Added Dynamic Content support to after submit actions
- Added option to disable form inputs inheriting the ID from the control name

### Changes

- Changed form elements settings distributtion

### Bug Fixes

- Fixed form actions PHP warning when evaluating execution conditions
- Fixed Form Area customizer status when there are multiple areas

## Version 1.7.7 - September 5, 2022

### New Features

- Added support for a form class attribute
- Added support for multiple form submit buttons
- Added support for datetime comparison in Dynamic Access rule

## Version 1.7.6 - August 26, 2022

### Bug Fixes

- Fixed error for auths with no threshold set

## Version 1.7.5 - August 24, 2022

### New Features

- Added form Friendly Captcha anti-spam solution

### Bug Fixes

- Fixed mailer 'sent from' support
- Fixed TikTok authentication workflow
- Fixed form hCaptcha custom error & label output
- Fixed form reCaptcha multiple instances & custom error output
- Fixed selecting presets from a custom Layout Library
- Fixed form date/time input validation when a min or max range is set

## Version 1.7.4 - August 15, 2022

### New Features

- Added UIkit List style modifiers support for Markdown element List Blocks
- Added Legend position, align and reverse settings for Chart element configuration

### Bug Fixes

- Fixed auth renewal token workflow
- Fixed YTP 3.b5 compatibility issue
- Fixed Markdown element nested list block rendering

## Version 1.7.3 - August 10, 2022

### Bug Fixes

- Fixed YTP 3.b4 compatibility issue
- Fixed form fields listing in actions settings panel

## Version 1.7.2 - July 26, 2022

### New Features

- Added support for loading collection icons set in Menu Items

### Changes

- Changed Upload form field filename input, is now expected without a file extension

### Bug Fixes

- Fixed offset execution of time and datemodify source filters
- Fixed includes evaluation of the access dynamic condition

## Version 1.7.1 - July 14, 2022

### Bug Fixes

- Fixed edge issue affecting core source transform

## Version 1.7.0 - July 12, 2022

### New Features

- Added Dynamic Addon 🥳

## Version 1.6.14 - July 11, 2022

### New Features

- Added Instagram User source query type

### Bug Fixes

- Fixed S3 Storage connection
- Fixed form init when assets are being combined

## Version 1.6.13 - June 21, 2022

### Bug Fixes

- Fixed regression affecting updates workflow

## Version 1.6.12 - June 21, 2022

### Bug Fixes

- Fixed UI regression affecting form actions

## Version 1.6.11 - June 16, 2022

### New Features

- Added Social Sharing element Xing network
- Added Social Sharing element Telegram network
- Added ValueInput setting for Google Sheet saveTo form action
- Added Google MyBusiness review comment translation fallback
- Added a Datetime picker field for the customizer

### Changes

- Changed and simplify Date/time access rules using the new Datetime picker

### Bug Fixes

- Fixed Social Sharing URL encoding
- Fixed SaveTo actions skipping empty fields
- Fixed reCaptcha form field assets execution
- Fixed Google MyBusiness multiple queries support
- Fixed double wp_once input ids in wordpress forms

## Version 1.6.10 - May 19, 2022

### Bug Fixes

- Fixed PHP warning regression

## Version 1.6.9 - May 17, 2022

### New Features

- Added IP Geolocation access rule
- Added Database source random ordering support

### Bug Fixes

- Fixed form empty tags parsing
- Fixed form Email Action plain text body parsing
- Fixed issue with form rendering on first page load after clearing cache

## Version 1.6.8 - April 20, 2022

### New Features

- Added Vimeo source
- Added hCaptcha form element
- Added HTML line breaks support for forms data placeholders

### Bug Fixes

- Fixed Layouts Manager renaming action
- Fixed Google My Business source AverageRating resolving

## Version 1.6.7 - April 6, 2022

### Bug Fixes

- Fixed icon loading regression
- Fixed edge PHP error in Google Spreadsheet source

## Version 1.6.6 - April 5, 2022

### Bug Fixes

- Fixed UI regressions

## Version 1.6.5 - April 4, 2022

### New Features

- Added Google reCaptcha v3 custom Score Threshold support

### Bug Fixes

- Fixed Google reCaptcha v3 not considering Score in validation
- Fixed assets build issue introduced in previous release

## Version 1.6.4 - April 1, 2022

### Changes

- Changed the ubication of Icon Collections manager to the Customizer -> Essentials menu

### Bug Fixes

- Fixed Google Sheet source columns start/end implementation

## Version 1.6.3 - March 24, 2022

### Bug Fixes

- Fixed Customizer UI issues
- Fixed Layouts Library presets loading
- Fixed Instagram and TikTok OAuth token renewal

## Version 1.6.2 - March 19, 2022

### Changes

- Changed YouTube cache minimum time to 3600 for all OAuth based queries

### Bug Fixes

- Fixed Freemium build
- Fixed YouTube API MaxResults filter being ignored

## Version 1.6.1 - March 17, 2022

### Bug Fixes

- Fixed Auth issues
- Fixed Builder fields loading

## Version 1.6.0 - March 16, 2022

### New Features

- Added Layouts Addon 🥳
- Added Storages module
- Added Essentials unified menu
- Added Settings Import/Export option
- Added About Section

## Version 1.5.16 - March 15, 2022

### New Features

- Added support for Multiple Items source on access rules dynamic configuration

### Bug Fixes

- Fixed SaveToGoogleSheet form action missing Sheet Name warning
- Fixed Google Sheet source schema issue when using multiple instances

## Version 1.5.15 - March 9, 2022

### Bug Fixes

- Fixed Database connection error handling (WordPress)
- Fixed CSV source Record filter/order missing field
- Fixed Email form ction multiple value placeholder parsing

## Version 1.5.14 - February 23, 2022

### New Features

- Added Dynamic access rule

### Bug Fixes

- Fixed vendor build issues regression
- Fixed Social Sharing element MailTo query encoding

## Version 1.5.13 - February 22, 2022

### New Features

- Added Week access rule
- Added Font Awesome 6 Free icon collection
- Added Email form action support for placeholder replacement in static attachments path
- Added Upload form field custom filename and avoid collisions settings

### Bug Fixes

- Fixed form Web Accessibility issue
- Fixed Social Sharing element MailTo query encoding
- Fixed Checkbox, Radio and Select form fields html entities encoding

## Version 1.5.12 - February 7, 2022

### Changes

- Icons Collections are no longer auto-fetched (WordPress)

### Bug Fixes

- Fixed Google My Business source comment resolving
- Fixed Email form field From and From Name settings support
- Fixed CSV source not working with Dynamic Content based filters
- Fixed forms validation issue on servers not respecting response code

## Version 1.5.11 - January 28, 2022

### Bug Fixes

- Fixed Social Sharing element MailTo query encoding
- Fixed YouTube API max results filter
- Fixed form ID generation related issues

## Version 1.5.10 - January 19, 2022

### New Features

- Added Redirect form action support for form fields data placeholders

### Bug Fixes

- Fixed external images caching
- Fixed Google My Business source Original Comment resolving
- Fixed forms Config Cache and Submission issues

## Version 1.5.9 - January 7, 2022

### Bug Fixes

- Fixed PHP egde warnings
- Fixed icons listing for MyIcons collection
- Fixed Auth grants returning no granted scopes
- Fixed Input form field rendering settings being ignored
- Fixed Google My Business source comment resolving

## Version 1.5.8 - November 30, 2021

### New Features

- Added form actions status setting
- Added TikTok source Start and Before Than filters to video query

### Bug Fixes

- Fixed Checkbox form field validation
- Fixed Limit argument being ignored in TikTok source video query
- Fixed Dynamic Values compatibility with certain access rules
- Fixed Upload form field final paths returned as absolute

## Version 1.5.7 - November 11, 2021

### New Features

- Added Download form action support for dynamic file path

### Bug Fixes

- Fixed Builder Foot Layout editing affected by form rendering

## Version 1.5.6 - November 5, 2021

### Bug Fixes

- Fixed regression introduced in previous release

## Version 1.5.5 - November 5, 2021

### Bug Fixes

- Fixed fields mapping issues with TikTok source

## Version 1.5.4 - November 3, 2021

### New Features

- Added form ID & Name attributes settings
- Added YouTube source API Key based Advanced Query
- Added Request source Timestamp mapping field a Date Modify filter
- Added Google My Business source Reviews Link, Original Review, and Translated Review mapping fields

### Bug Fixes

- Fixed form Download action
- Fixed form Radio field HTML 5 validation
- Fixed Google MyBusiness source business open hours query

## Version 1.5.3 - October 22, 2021

### Changes

- Refactored YouTube API calls to mitigate quota limits

### Bug Fixes

- Fixed forms actions regression
- Fixed core sources ID resolving (WordPress)
- Fixed CSV source default ordering and headers encoding issue

## Version 1.5.2 - October 15, 2021

### Bug Fixes

- Fixed forms actions migration script
- Fixed CSV source header names display when listed in filter/ordering conditions

## Version 1.5.1 - October 14, 2021

### Bug Fixes

- Fixed CSV source encoding regression
- Fixed Google My Business source locations list limit

## Version 1.5.0 - October 14, 2021

### New Features

- Added TikTok source
- Added YouTube source
- Added Request source
- Added Google MyBusiness source
- Added Cloudflare Stream source
- Added Filter/Order query conditions to CSV source
- Added Hashtagged Media query to Instagram source
- Added Save to Google Sheet form action
- Added ID attribute setting to all form fields
- Added User access rule

## Version 1.4.10 - October 11, 2021

### Bug Fixes

- Fixed Chart Element missing enclosing div
- Fixed Email Action test email execution
- Fixed access rules mapping with Dynamic Content
- Fixed Warning if initial icon collections fail to download (WordPress)

## Version 1.4.9 - October 4, 2021

### Bug Fixes

- Fixed update transform warning

## Version 1.4.8 - September 30, 2021

### Bug Fixes

- Fixed form field tags not being removed when replacement is not found in the submission data
- Fixed regression introduced in previous release

## Version 1.4.7 - September 21, 2021

### Bug Fixes

- Fixed sources field name edge case encoding issue
- Fixed Icon Picker filter not resetting the group when switching collection

## Version 1.4.6 - September 13, 2021

### Bug Fixes

- Fixed Chart Element general container settings not being applied
- Fixed Google Sheet source not considering custom cache time setting
- Fixed form Upload field support for multiple file uploads
- Fixed Email form action not sending text/plain alternative body when in HTML mode
- Fixed Email, Tel, and URL form fields support for pattern attribute

## Version 1.4.5 - August 31, 2021

### Bug Fixes

- Fixed icons loading regression
- Fixed form Honeypot validation
- Fixed form Upload field support for spaces in control name

## Version 1.4.4 - August 20, 2021

### Changes

- Removed ZipArchive PHP extension dependency

### Bug Fixes

- Fixed send email test workflow for Email form action
- Fixed compatibility with YOOtheme Pro 2.6 GraphQL dependency

## Version 1.4.3 - August 11, 2021

### Bug Fixes

- Fixed issue on Database source when linking more than one relation to the same field
- Fixed issue on Database source when using filtering/ordering on external database
- Fixed UI issue affecting access rules modal

## Version 1.4.2 - August 4, 2021

### Bug Fixes

- Fixed source types extension issue (Joomla)

## Version 1.4.1 - August 4, 2021

### Bug Fixes

- Fixed source types extension issue (WordPress)

## Version 1.4.0 - August 3, 2021

### New Features

- Added Table Relations and Single Record Query for Database source
- Added Filters and Ordering conditions for Database source queries
- Added dynamic content support to form fields
- Added access composable conditions with AND/OR logic
- Added source support for Dynamic access rule
- Added setting to name an access rule
- Added setting to disable an access rule
- Added setting to revers an access rule evaluation
- Added Social Sharing element MailTo item
- Added Social Sharing element Viber item
- Added Table and List blocks support for Markdown element

## Version 1.3.6 - August 3, 2021

### Bug Fixes

- Fixed error triggered if a CSV source file is missing

## Version 1.3.5 - July 28, 2021

### Bug Fixes

- Fixed warning triggered by Icon Loader
- Fixed invisible reCAPTCHA regression introduced in v1.3.4

## Version 1.3.4 - July 13, 2021

### Bug Fixes

- Fixed multiple forms support when using reCAPTCHA
- Fixed Language access rule language listing (Joomla)

## Version 1.3.3 - July 1, 2021

### Bug Fixes

- Fixed Limit filter being ignored in Database source
- Fixed Email form field allowing whitespaces where it should not
- Fixed Download form action not redirecting properly

## Version 1.3.2 - June 29, 2021

### Bug Fixes

- Fixed Instagram source raw media url
- Fixed regression when saving config

## Version 1.3.1 - June 28, 2021

### Bug Fixes

- Fixed Database source fields keys encoding
- Fixed Database source external connection support
- Fixed Instagram source image caching for videos media
- Fixed Form Area quick edit access link
- Fixed Icon Collections loading and UI inconsistencies
- Fixed Chart element dimension enforcement & deferred init

## Version 1.3.0 - June 23, 2021

### New Features

- Added support for overriding OAuth settings allowing the usage of custom Apps
- Added Settings -> Auths section for managing authentications and secrets in one place
- Added more transparency about what permission scopes are required in an OAuth authentication
- Added Hashtags mapping field for Instagram source
- Added resizing support for Instagram source image media
- Added Comments Count and Like Count mapping fields for Instagram source (Business Accounts)
- Added Date and Content Limit filters for Timestamp and Caption Instagram source mapping fields
- Added Database source external database connection support
- Added Google Sheet source support for specifying the Spreadsheet Sheet
- Added Download form action
- Added Email from action From Name setting
- Added Redirect form action Timeout and Open in New Window settings
- Added Honeypot as a form CAPTCHA alternative
- Added Icons List view and other UI improvements to the Builder Icons Picker
- Added Icon Collections Manager with on-demand installation (Icons are not included in the build anymore)
- Added new icon collections (Ant Design, Bootstrap, Boxicons, Feather, Octicons, Remix Icon & Tabler Icons)

## Version 1.2.11 - June 23, 2021

### Bug Fixes

- Fixed Instagram source media type filtering not including Carousel Albums
- Fixed issue with reCAPTCHA form field when HTML5 validation is disabled and server validation fails

## Version 1.2.10 - June 1, 2021

### Changes

- Changed Message form action modal as alert instead of dialog (reversed again)

### Bug Fixes

- Fixed reCAPTCHA form field regresion
- Fixed Email form action Send as HTML setting default value

## Version 1.2.9 - May 18, 2021

### Bug Fixes

- Fixed Chart element data encoding being affected in some edge configurations
- Fixed unwanted downgrades when auto-updating (Joomla)

## Version 1.2.8 - May 16, 2021

### Changes

- Removed Instagram Personal source unsupported mapping fields, comments_count & like_count

### Bug Fixes

- Fixed form field reCAPTCHA not working when used in multiple forms on the same page
- Fixed form being reset too early affecting submissions with custom action url

## Version 1.2.7 - April 28, 2021

### Bug Fixes

- Fixed PHP warning related to automatic updates (WordPress)
- Fixed Google Sheet source warning when loading an empty spreadsheet
- Fixed Google Sheet source content caching
- Fixed Instagram source refresh token issue
- Fixed icons collections build
- Fixed icons invalid cache key warning

## Version 1.2.6 - April 13, 2021

### New Features

- Added Horizontal layout for Checkbox & Radio form fields
- Added Grid Columns setting for Input form field

### Bug Fixes

- Fixed form fields listing in the builder
- Fixed SaveToCSV form action multi options fields parsing
- Fixed form rendering after cache clearance

## Version 1.2.5 - April 7, 2021

### New Features

- Added sources configuration pre saving tests

### Changes

- Changed form actions JavaScript hooks workflow

### Bug Fixes

- Fixed conflicts with 3rd-parties dependencies
- Fixed sources mapping regression
- Fixed Checkbox form field validation
- Fixed Email form action attachments support

## Version 1.2.4 - March 27, 2021

### Bug Fixes

- Fixed form settings being deleted when Form Area is disabled

## Version 1.2.3 - March 25, 2021

### Changes

- Changed YOOtheme Pro min version to 2.3.32

### Bug Fixes

- Fixed UI regression
- Fixed Date form field min/max attributes support

## Version 1.2.2 - March 21, 2021

### New Features

- Added Upload form field support for Tag Replacement in upload path

### Bug Fixes

- Fixed general UI issues
- Fixed Email action sending test & attachments related issues

## Version 1.2.1 - March 13, 2021

### Changes

- Changed YOOtheme Pro min version to 2.3.18

### Bug Fixes

- Fixed Checkbox form field values submission
- Fixed Icons picker loader regression

## Version 1.2.0 - March 10, 2021

### New Features

- Added Sources Addon 🥳
- Added quick access Form Area icon
- Added support for spaces in form field names
- Added support for custom form node action url and method
- Added a simple send email test for Email form action
- Added Columns configuration for SaveToCSV form action
- Added optional name field to form actions for easier identification
- Added support for empty value and disabled attribute in Select, Radio and Checkbox form fields,
- Added support for Submission Tag Replacement on missing Email action fields, ccs, bccs, reply_tos and from
- Added Datetime access rule
- Added Access builder status icon
- Added Pinterest network for Social Sharing element
- Added Social Sharing element popup window option

### Changes

- Changed Message action to use UIkit modal dialog instead of alert
- Changed User access rule to display Guest setting as a role (WordPress)

## Version 1.1.8 - March 9, 2021

### Bug Fixes

- Fixed session handler (WordPress)
- Fixed Upload form field validation and edge configuration issues
- Fixed Email form action Send Email as HTML default fallback
- Fixed Save to CSV form action headers format when using custom delimiter

## Version 1.1.7 - February 22, 2021

### Bug Fixes

- Fixed form related PHP warnings
- Fixed Upload form field mimetype validation
- Fixed support for multiple forms rendering on the same page
- Fixed icon related PHP warnings
- Fixed support for multiple icons declared as HTML in the same field

## Version 1.1.6 - February 10, 2021

### Bug Fixes

- Fixed plugin execution time (WordPress)
- Fixed support for icons set as HTML
- Fixed Fieldset form field Horizontal Layout display
- Fixed Email form action multiple emails parsing
- Fixed Input form field attributes rendering

## Version 1.1.5 - January 9, 2021

### Bug Fixes

- Fixed MyIcons tab display (WordPress)

## Version 1.1.4 - December 23, 2020

### Bug Fixes

- Fixed icons Builder integration for pre YOOtheme Pro 2.3
- Fixed Email form action issue in admin-selected email attachments

## Version 1.1.3 - November 26, 2020

### Bug Fixes

- Fixed assets loading in multilingual sites (WordPress)
- Fixed auto-update issue (WordPress)

## Version 1.1.2 - November 25, 2020

### Changes

- Improved form fields placeholders
- Improved form actions settings
- Improved Form Area status evaluation
- Improved access rules settings

### Bug Fixes

- Fixed update checking support (WordPress)
- Fixed Range form field php warning on new instances
- Fixed form fields control name fallback
- Fixed Email form action empty attachments being sent
- Fixed Season access rule evaluation
- Fixed User access rule guest user validation (WordPress)
- Fixed access Date & Time evaluation if only one value is set

## Version 1.1.1 - October 29, 2020

### Bug Fixes

- Fixed SaveToCSV form action typo causing a PHP warning

## Version 1.1.0 - October 28, 2020

### New Features

- Added Upload form field extensions and mimetype HTML validation
- Added SaveToCSV form action delimiter and enclosure params
- Added Browser, Device, Operative System, IP Address, Day, Month, Season and Time access rules
- Added icons picker new UI with grouped collections, lazy loading and global search

### Changes

- Removed deprecated Submit form element
- Removed Range form field required attribute as natively is not supported
- Removed Markdown element file setting in favor of File source
- Changed Datetime access rules timezone, is now assumed from the server configuration

### Bug Fixes

- Fixed Upload form field file size validation
- Fixed Upload form field file overrides when file name collides
- Fixed icons rendering on cached articles (Joomla)

## Version 1.1.0-beta.4 - October 13, 2020

### New Features

- Added spinner to Submit button

### Bug Fixes

- Fixed form validation errors display
- Fixed Radio and Checkbox form fields template rendering issues
- Fixed build issue affecting icon collections

## Version 1.1.0-beta.3 - October 7, 2020

### New Features

- Added text editor for Checkbox and Radio form field options
- Added support for icons set as html element using uk-icon attribute

### Bug Fixes

- Fixed Input element nodes type

## Version 1.1.0-beta.2 - October 6, 2020

### New Features

- Added Button element with support for Submit and Reset buttons
- Added warn if reCaptcha was set more than once in the same Form Area

### Changes

- Deprecated Submit form field element

### Bug Fixes

- Fixed form Fieldset list of allowed fields
- Fixed form Input fields missing icon settings
- Fixed form elements attribute rendering
- Fixed form reCaptcha validation
- Fixed form Redirect action
- Fixed icon collections ubication
- Fixed icon loading in modules (Joomla)

## Version 1.0.2 - October 5, 2020

### Bug Fixes

- Fixed Language access rule (Joomla)
- Fixed icon loading in footer and modules

## Version 1.1.0-beta - October 1, 2020

### New Features

- Added Forms Addon 🥳
- Added URL access rule
- Added Guest option for User access rule (WordPress)
- Added Teenyicons icon collection
- Added method to add custom icon collections directories
- Added Social Sharing element Title option
- Added Chart element Dynamic Content support

### Changes

- Refactored core icon collections
- Refactored Social Sharing element networks listing
- Renamed Icons Providers as Icons Collections

### Bug Fixes

- Fixed Social Sharing custom icon rendering
- Fixed Social Sharing element icon custom size setting
- Fixed Chart element Pie and Doughnut rendering

## Version 1.0.1 - September 26, 2020

### Bug Fixes

- Fixed Icons rendering when the field is set on a parent element

## Version 1.0.0 - July 22, 2020

### New Features

- Added Markdown element
- Added Social Sharing element Advanced fields
- Added Social Sharing element LinkedIn network
- Added plugin auto-update support
- Added My Icons tab in icon picker
- Added elements attributes settings
- Added strict option for user related access rules
- Added icons support in buttons
- Added icons custom collections support
- Added filter by name query for the icons selection modal

### Bug Fixes

- Fixed Joomla! install dependency check
- Fixed icon picker modal performance when listing large amount of items
- Fixed social sharing links target
- Fixed social sharing WhatsApp link
- Fixed social sharing icon consistency for predefined networks
- Fixed icon collections support for attributes hard coded in value
- Fixed Date access rule help description and format parsing
- Fixed icons listing regression
- Fixed Chart element data decimals input
- Fixed Language access rule (WordPress)
- Fixed User access rule (WordPress)
- Fixed icons rendering (WordPress)
- Fixed Chart element support for mixed charts

## Version 1.0.0-beta - June 17, 2020

- First Release 🥳
