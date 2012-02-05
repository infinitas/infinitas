Copyright 2009 - 2010, Cake Development Corporation
                        1785 E. Sahara Avenue, Suite 490-423
                        Las Vegas, Nevada 89104
                        http://cakedc.com

CakePHP Tags Plugin - heavily modified 

The tags plugin includes the Taggable Behavior that allows you to simply tag
everything. It saves all tags in a tags table and connects any kind of records
to them through the tagged table. You can specify alternate tables for both in
the case you get *A LOT* records tagged.


To make something taggable you just need to do two things:
 * Attach the taggable behavior
 * Add a 'tags' field into your view for the model you just made taggable


The taggable behavior can be configured using the following parameters
 * separator				- separator used to enter a lot of tags, comma by default
 * tagAlias					- model alias for Tag model
 * tagClass					- class name of the model storing the tags
 * taggedClass				- class name of the HABTM association table between tags and models
 * field					- the fieldname that contains the raw tags as string
 * foreignKey				- foreignKey used in the HABTM association
 * associationForeignKey	- associationForeignKey used in the HABTM association
 * automaticTagging			- if set to true you don't need to use saveTags() manually
 * language					- only tags in a certain language, string or array


The Tagged model contains a method _findCloud which can be used like any other
find $this->Model->find('cloud', $options);

The result contains a "weight" field which has a normalized size of the tag
occurrence set. The min and max size can be set by passing 'minSize" and
'maxSize' to the query. This value can be used in the view to control the size 
of the tag font.

The MIT License

Copyright 2009 - 2010, Cake Development Corporation
                        1785 E. Sahara Avenue, Suite 490-423
                        Las Vegas, Nevada 89104
                        http://cakedc.com

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.