# Q2A-q2a-extra-question-field-Plugin
Enhance your Extra Question field with file management
/*******************************************************************/
Extra Question Field plugin for question2question
/*******************************************************************/

/*-----------------------------------------------------------------*/
1. Summary
/*-----------------------------------------------------------------*/
This package is plugin for question2question.

question2question: http://www.question2question.org/

/*-----------------------------------------------------------------*/
2. Feature of this plugin
/*-----------------------------------------------------------------*/
1. Add extra field on question. (textbox, textarea, checkbox, select, select-radio, file)

/*-----------------------------------------------------------------*/
3. Version compatibility
/*-----------------------------------------------------------------*/
question2question V1.6 later

/*-----------------------------------------------------------------*/
4. Installation/Settings
/*-----------------------------------------------------------------*/
1.Unzip archive any local folder.
2.Upload q2a-extra-question-field folder under qa-plugin folder.
3.Log in administrator account.
4.Select admin -> plugins menu.
5.After setting, and save.

/*-----------------------------------------------------------------*/
5. Uninstallation
/*-----------------------------------------------------------------*/
1.Log in administrator account.
2.Select admin -> plugins menu.
3.Click reset button.
4.Delete q2a-extra-question-field folder under qa-plugin folder.

/*-----------------------------------------------------------------*/
6. Options
/*-----------------------------------------------------------------*/
[Extra field count]
The number of the required extended fields is chosen. 

[Max file size]
Maximum sile size which user can upload.

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

/*-----------------------------------------------------------------*/
7. License / Disclaimer
/*-----------------------------------------------------------------*/
1.This software obeys license of Question2Answer.
2.The author does not always promise to support.
3.The author does not compensate you for what kind of damage that you used question2question or this file.

/*-----------------------------------------------------------------*/
8. Author/Creator
/*-----------------------------------------------------------------*/
handle: sama55
site: http://cmsbox.jp/

/*-----------------------------------------------------------------*/
9. Version history
/*-----------------------------------------------------------------*/
■[2013/04/21] V1.0		First Release
■[2013/04/23] V1.1		Add option [Hide on question page if blank]
■[2013/04/23] V1.2		Add option [Attributes]
							Sanitize of input string.
■[2013/04/25] V1.3		Add option [Explanation on question form - HTML allowed]
							Add option [Position on question form]
							Add option [Position on question page]
■[2013/05/07] V1.3.1	Fix bug [Attributes Error in plugin setting page].
							Fix bug [Check of extra fields(4...)]
■[2013/05/08] V1.3.2	Fix bug [Error when unlogin].
■[2013/05/23] V1.3.3	Change author site.
■[2013/10/19] V1.4		Add Option (Input is required)
■[2013/12/05] V1.5		Add textarea and file type.
							Fixed a lot of bugs.
■[2013/12/13] V1.6		Add lightbox effect feature.
■[2014/01/09] V1.6.1	Fixed bug when question is hided.
■[2014/03/13] V1.6.2	Fixed bug (High level user post is not stored on post moderation mode)
■[2014/03/16] V1.6.3	Fixed bug (Error on none description question)
■[2014/09/19] V1.6.4	Fixed bug (If attachment files exist on upper content position, popup don't work)
■[2015/02/04] V1.7		Add extension check feature in case of file type field.

Have fun !!
