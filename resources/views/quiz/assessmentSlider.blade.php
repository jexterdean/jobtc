@if(count($user) > 0)
    <ul class="list-group user-list-slider">
    @foreach($user as $k=>$v)
        <li class="list-group-item{{ $k > 4 ? ' hidden' : '' }}" data-id="min_id" data-total="{{ $v->points_tags_total }}">
            <div class="media">
                <div class="media-body">
                    <h4 class="media-heading">{{ $v->name }}</h4>
                    <?php
                    if(count($v->tags) > 0){
                        $ref = 0;
                        foreach($v->tags as $tags=>$points){
                            $displayed_points = $points;
                            if(count($slide_setting) > 0){
                                if(array_key_exists($tags, $slide_setting)){
                                    $displayed_points = $points + ($points * ($slide_setting->$tags/100));
                                }
                            }
                            $points_width = number_format(($displayed_points != 0 && $max_points_per_test[$v->test_id] != 0 ? ($displayed_points/$max_points_per_test[$v->test_id] * 100) : $displayed_points), 2);
                            $points_width = $displayed_points > $max_points_per_test[$v->test_id] ? 100 : $points_width;
                            ?>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <strong>{{ strtoupper($tags) }}</strong>
                                </div>
                                <div class="col-md-9">
                                    <div class="progress">
                                        <div data-tag="{{ $tags }}" data-points="{{ $points }}" data-maxpoints="{{ $max_points_per_test[$v->test_id] }}" class="progress-bar progress-bar-{{ $progressColor[$ref % 4] }}" role="progressbar" aria-valuenow="{{ $displayed_points }}" aria-valuemin="0" aria-valuemax="{{ $max_points_per_test[$v->test_id] }}" style="{{ 'width: ' . $points_width . '%;' }}">
                                            {{ number_format($displayed_points, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $ref ++;
                        }
                    }
                    ?>
                </div>
            </div>
        </li>
    @endforeach
    </ul>
@endif