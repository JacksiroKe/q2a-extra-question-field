# Q2A Extra Question Field Plugin
Add extra field on question. (textbox, textarea, checkbox, select, select-radio, file). Enhance your Extra Question field with file management.

## Version compatibility
Question2Answer V1.6 and later

## Installing Instructions
If you have never installed my Q2A plugins before please consider checking the [Installation Guide](https://github.com/JacksiroKe/q2a-extra-question-field/edit/master/INSTALLING.md)

## Options
### Extra field count:
 * The number of the required extended fields is chosen. 
### Max file size:
 * Maximum sile size which user can upload.
### Permit only images (jpeg,png,gif):
 * OFF: The user can upload file except image.
 * ON:  The user can't upload file except image.
### Max image width:
 * When image is stored, image is resized to this width.
### Max image height:
 * When image is stored, image is resized to this height.
### Thumbnail width of attached image:
 * Thumbnail image is displayed on question and editing page with this size.
### Enable lightbox effect:
 * OFF: Images are displayed in another window.
 * ON:  Images are displayed by jQuery lightbox plugin.
### Enable field:
 * Enable field option
### Label on question form:
 * Asign label on question form. This option is required.
### Explanation on question form - HTML allowed:
 * Asign explanation on question form.
### Type:
 1. Select field type.
 2. Textarea ex: 5 (this is rows)
 3. Checkbox ex: OFF==0||ON==1 / NO==0||YES==1
 4. Select/Radio ex: ||label1==value1||label2==value2||label3==value3
 5. File ex:Combine allowed file extensions by comma(,)
 6. Program ex: @EVAL return '||label1==value1||label2==value2';
### Attributes: Note: Other attributes of input tag in accordance with rule of HTML.
* Textbox ex: maxlength="20"
* Select ex: size="3"
### Default value:
 * Input default value first displayed on question form.
### Position on question form:
 * Select position on question form.
### Show on question page:
 * Display fieldX on question page.
### Label on question page:
 * Asign label on question page. If check "Show on question page", this option is required.
### Position on question page::
 * Select position on question page.
### Hide on question page if blank::
 * If input value is blank, hide fieldX on question page.
### Input is required:
 * If input value is blank, form will be error.
