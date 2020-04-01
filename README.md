# Q2A Extra Question Field Plugin
Add extra field on question. (textbox, textarea, checkbox, select, select-radio, file). Enhance your Extra Question field with file management.

## Version compatibility
Question2Answer V1.6 and later

## Installation
1. Unzip archive any local folder.
2. Upload q2a-extra-question-field folder under qa-plugin folder.
3. Log in administrator account.
4. Select Admin -> Plugins menu.
5. (Q2A V1.8) Enable plugin and Save
6. Go to Admin -> Extra Question Field (admin/eqf_admin)

## Uninstallation
1. Log in administrator account.
2. Select Admin -> Extra Question Field (admin/eqf_admin)
3. Click reset button.
4. Delete q2a-extra-question-field folder under qa-plugin folder.

## Options
* Extra field count: The number of the required extended fields is chosen. 
* Max file size: Maximum sile size which user can upload.

[Permit only images (jpeg,png,gif)]
OFF: The user can upload file except image.
ON:  The user can't upload file except image.

[Max image width]
When image is stored, image is resized to this width.

[Max image height]
When image is stored, image is resized to this height.

[Thumbnail width of attached image]
Thumbnail image is displayed on question and editing page with this size.

[Enable lightbox effect]
OFF: Images are displayed in another window.
ON:  Images are displayed by jQuery lightbox plugin.

[Enable fieldX]
Enable fieldX

[Label on question form]
Asign label on question form. This option is required.

[Explanation on question form - HTML allowed]
Asign explanation on question form.

[Type]
Select field type.

[Options]
Note: Textarea ex: 5 (this is rows)
Checkbox ex: OFF==0||ON==1 / NO==0||YES==1
Select/Radio ex: ||label1==value1||label2==value2||label3==value3
File ex:Combine allowed file extensions by comma(,)
Program ex: @EVAL return '||label1==value1||label2==value2';

[Attributes]
Note: Other attributes of input tag in accordance with rule of HTML.
Textbox ex: maxlength="20"
Select ex: size="3"

[Default value]
Input default value first displayed on question form.

[Position on question form]
Select position on question form.

[Show on question page]
Display fieldX on question page.

[Label on question page]
Asign label on question page.
If check "Show on question page", this option is required.

[Position on question page]
Select position on question page.

[Hide on question page if blank]
If input value is blank, hide fieldX on question page.

[Input is required]    -- Added V1.4 --
If input value is blank, form will be error.

