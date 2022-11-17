<?php

class Sample extends CI_Controller {

	public function index() {
		$this->load->helper("myparser");
		$html = <<<HTML

		[Styles]
		<style>
		.navbar-default{
		    position: fixed !important;
		    width: 100% !important;
		    margin: 0 0 405px 0 !important;
		}

		@media(min-width:768px) {
		    #page-wrapper {
		        position: inherit;
		        margin: 0 0 0 250px;
		        padding: 52px 0 0 30px;/* for fixed menu*/ /*padding: 0 30px;*/ 
		        border-left: 1px solid #e7e7e7;
		    }
		}
		</style>
		[/Styles]

	[Contents]
	<p>orem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus rhoncus nunc volutpat efficitur. Curabitur volutpat vulputate odio, a vestibulum nulla suscipit vel. Etiam faucibus nisi eget augue auctor, et fringilla arcu pellentesque. Aliquam nec porttitor quam, in imperdiet odio. Duis feugiat auctor eros. Duis nulla tortor, consequat eget quam a, posuere finibus dolor. In non justo sed magna vestibulum fringilla. Praesent sit amet nisi non quam commodo convallis eu id augue. Duis venenatis tellus sed tincidunt mollis. Nulla facilisi. Etiam blandit est a sollicitudin aliquam. Aliquam quis sapien vitae nisl rutrum scelerisque sed vitae eros. Nullam sit amet odio eu quam vestibulum viverra. Quisque sit amet ornare leo, id luctus arcu. In hac habitasse platea dictumst.

Aliquam cursus, quam sed sollicitudin condimentum, nibh nunc ullamcorper sapien, in vestibulum nibh leo eget dolor. Donec id augue a sapien venenatis lacinia consequat ut enim. Aenean aliquet sem diam, at ornare nulla tincidunt tristique. Nam a est a dolor blandit faucibus. Aenean tempus arcu nec nibh cursus, in auctor mauris gravida. Donec condimentum auctor tellus, ut fringilla neque volutpat eu. Aenean sit amet massa non est molestie lacinia sed et justo. Nulla ultrices porttitor sem vel convallis. Nulla suscipit porta felis, in lobortis arcu dignissim non. Aenean sagittis sit amet augue sed feugiat. Maecenas pretium dapibus feugiat. Quisque vestibulum lacus quis ante hendrerit, ut varius sem euismod.

Quisque condimentum ligula volutpat, fringilla est quis, dictum tellus. Integer ornare libero est, id ultricies magna placerat et. Aenean facilisis nunc sit amet leo viverra tempor. Nulla eleifend libero quis nisl tincidunt, eu lacinia risus facilisis. Fusce tempus mi velit. Aliquam tempus, sapien quis mattis faucibus, orci odio tincidunt enim, vitae imperdiet ligula metus quis arcu. In sed sapien metus. Donec auctor massa eu dui dictum vulputate. Donec ut odio mauris. Vestibulum commodo quam in ligula placerat fringilla quis sed augue. Sed nec pellentesque ante. Nullam ultricies nisl metus, vitae dictum ligula cursus et. Aenean vitae imperdiet mi, ut facilisis lacus. Donec suscipit ac velit eget dignissim.

Aenean sodales neque dolor, id ornare nibh dictum sit amet. Integer nec interdum felis, eu luctus lorem. Morbi commodo, odio sit amet facilisis fermentum, turpis elit vestibulum odio, vel finibus justo neque sit amet nisi. Vivamus vitae mollis diam. Vestibulum pharetra non ipsum in aliquet. Aenean vestibulum tortor nec risus iaculis scelerisque. Donec ante libero, lacinia quis neque quis, iaculis lobortis arcu. Nam porttitor, magna nec feugiat tincidunt, sapien augue efficitur eros, et tincidunt dui erat ac libero. Donec tortor eros, pretium ac dapibus id, euismod quis ligula. Duis sagittis luctus quam id scelerisque. Ut eu porttitor risus. Nunc scelerisque imperdiet elementum. Nullam eleifend felis a eleifend suscipit. Donec quis varius lacus, eu scelerisque nisi.

Quisque id sollicitudin justo, eu mattis purus. Integer fringilla metus eros, sed elementum odio maximus nec. In dolor lacus, lacinia vitae laoreet ullamcorper, tincidunt in metus. Phasellus molestie, nulla semper volutpat feugiat, risus turpis tincidunt sem, vel maximus augue ante vel purus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi dictum posuere nunc id faucibus. Nunc euismod finibus sem sed volutpat. Mauris eget neque interdum, varius ligula ut, commodo nisl. 
</p>
	[/Contents]

HTML;

	$this->load->view("sample", getviewparts($html));
	}

}