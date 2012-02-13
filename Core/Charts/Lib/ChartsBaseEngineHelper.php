<?php
	/**
	 * @brief Base class for chart engines.
	 *
	 * This just defines a few of the more common types that would be used, they
	 * will just throw errors if used and the selected engine does not support the
	 * chosen type.
	 *
	 * @link http://wikipedia.org for descriptions of charts
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Charts.libs
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ChartsBaseEngineHelper extends AppHelper {
		/**
		 * @brief draw an area chart
		 *
		 * An area chart or area graph displays graphically quantitive data. It
		 * is based on the line chart. The area between axis and line are commonly
		 * emphasized with colors, textures and hatchings. Commonly one compares
		 * with an area chart two or more quantities.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function area($data){
			trigger_error(sprintf(__('%s does not have area() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a bar chart
		 *
		 * A bar chart or bar graph is a chart with rectangular bars with lengths
		 * proportional to the values that they represent. The bars can also be
		 * plotted horizontally. Bar charts are used for plotting discrete (or
		 * 'discontinuous') data i.e. data which has discrete values and is not
		 * continuous.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function bar($data){
			trigger_error(sprintf(__('%s does not have bar() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a box chart
		 *
		 * Box charts, also called box plots or box and whisker charts, are a type
		 * of chart that shows the grouping of one or more series into quartiles
		 * (quartiles are groups that span 25% of the range of values, with the
		 * possible exception of outliers).
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function box($data){
			trigger_error(sprintf(__('%s does not have box() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a candlestick chart
		 *
		 * A candlestick chart is a style of bar-chart used primarily to describe
		 * price movements of a security, derivative, or currency over time. It
		 * is a combination of a line-chart and a bar-chart, in that each bar
		 * represents the range of price movement over a given time interval.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function candlestick($data){
			trigger_error(sprintf(__('%s does not have candlestick() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a gauge chart
		 *
		 * Generate a meter type chart like the google-o-meter
		 * http://code.google.com/apis/chart/docs/gallery/googleometer_chart.html
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function gauge($data){
			trigger_error(sprintf(__('%s does not have gauge() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a line chart
		 *
		 * A line chart or line graph is a type of graph, which displays
		 * information as a series of data points connected by straight line
		 * segments. It is a basic type of chart common in many fields. It is an
		 * extension of a scatter graph, and is created by connecting a series
		 * of points that represent individual measurements with line segments.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function line($data){
			trigger_error(sprintf(__('%s does not have line() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a organization chart
		 *
		 * An organizational chart (often called organization chart, org chart,
		 * organigram(me), or organogram(me)) is a diagram that shows the structure
		 * of an organization and the relationships and relative ranks of its
		 * parts and positions/jobs. The term is also used for similar diagrams,
		 * for example ones showing the different elements of a field of knowledge
		 * or a group of languages.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function organization($data){
			trigger_error(sprintf(__('%s does not have organization() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a pie chart
		 *
		 * A pie chart (or a circle graph) is a circular chart divided into sectors,
		 * illustrating proportion. In a pie chart, the arc length of each sector
		 * (and consequently its central angle and area), is proportional to the
		 * quantity it represents.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function pie($data){
			trigger_error(sprintf(__('%s does not have pie() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a radar chart
		 *
		 * A radar chart is a graphical method of displaying multivariate data
		 * in the form of a two-dimensional chart of three or more quantitative
		 * variables represented on axes starting from the same point. The relative
		 * position and angle of the axes is typically uninformative. The radar
		 * chart is also known as web chart, spider chart, star chart, cobweb
		 * chart, star plot, irregular polygon, polar chart, or kiviat diagram.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function radar($data){
			trigger_error(sprintf(__('%s does not have radar() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a scatter chart
		 *
		 * A scatter plot or scattergraph is a type of mathematical diagram using
		 * Cartesian coordinates to display values for two variables for a set of
		 * data. The data is displayed as a collection of points, each having the
		 * value of one variable determining the position on the horizontal axis
		 * and the value of the other variable determining the position on the.
		 * vertical axis.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function scatter($data){
			trigger_error(sprintf(__('%s does not have scatter() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief Convert data into a table
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function table($data){
			trigger_error(sprintf(__('%s does not have table() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a treemap
		 *
		 * Treemaps display hierarchical (tree-structured) data as a set of nested
		 * rectangles. Each branch of the tree is given a rectangle, which is
		 * then tiled with smaller rectangles representing sub-branches. A leaf
		 * node's rectangle has an area proportional to a specified dimension on
		 * the data.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function treemap($data){
			trigger_error(sprintf(__('%s does not have treemap() implemented'), get_class($this)), E_USER_WARNING);
		}

		/**
		 * @brief draw a venn diagram
		 *
		 * Venn diagrams or set diagrams are diagrams that show all hypothetically
		 * possible logical relations between a finite collection of sets
		 * (aggregation of things). They are used to teach elementary set theory,
		 * as well as illustrate simple set relationships in probability, logic,
		 * statistics, linguistics and computer science.
		 *
		 * @param array $data
		 * @access public
		 * @throws E_USER_WARNING this method needs to be implemented in the engine
		 *
		 * @return string
		 */
		public function venn($data){
			trigger_error(sprintf(__('%s does not have venn() implemented'), get_class($this)), E_USER_WARNING);
		}
	}