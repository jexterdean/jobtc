{"filter":false,"title":"show.blade.php","tooltip":"/resources/views/discussions/show.blade.php","undoManager":{"mark":100,"position":100,"stack":[[{"start":{"row":139,"column":42},"end":{"row":139,"column":43},"action":"insert","lines":["e"],"id":578}],[{"start":{"row":139,"column":43},"end":{"row":139,"column":44},"action":"insert","lines":[" "],"id":579}],[{"start":{"row":139,"column":44},"end":{"row":139,"column":45},"action":"insert","lines":["b"],"id":580}],[{"start":{"row":139,"column":44},"end":{"row":139,"column":45},"action":"remove","lines":["b"],"id":581}],[{"start":{"row":139,"column":43},"end":{"row":139,"column":44},"action":"remove","lines":[" "],"id":582}],[{"start":{"row":139,"column":43},"end":{"row":139,"column":44},"action":"insert","lines":["-"],"id":583}],[{"start":{"row":139,"column":44},"end":{"row":139,"column":45},"action":"insert","lines":["b"],"id":584}],[{"start":{"row":139,"column":45},"end":{"row":139,"column":46},"action":"insert","lines":["d"],"id":585}],[{"start":{"row":139,"column":46},"end":{"row":139,"column":47},"action":"insert","lines":["y"],"id":586}],[{"start":{"row":139,"column":46},"end":{"row":139,"column":47},"action":"remove","lines":["y"],"id":587}],[{"start":{"row":139,"column":45},"end":{"row":139,"column":46},"action":"remove","lines":["d"],"id":588}],[{"start":{"row":139,"column":45},"end":{"row":139,"column":46},"action":"insert","lines":["o"],"id":589}],[{"start":{"row":139,"column":46},"end":{"row":139,"column":47},"action":"insert","lines":["d"],"id":590}],[{"start":{"row":139,"column":47},"end":{"row":139,"column":48},"action":"insert","lines":["y"],"id":591}],[{"start":{"row":143,"column":18},"end":{"row":143,"column":19},"action":"insert","lines":["<"],"id":592}],[{"start":{"row":143,"column":19},"end":{"row":143,"column":20},"action":"insert","lines":["!"],"id":593}],[{"start":{"row":143,"column":20},"end":{"row":143,"column":21},"action":"insert","lines":["-"],"id":594}],[{"start":{"row":143,"column":21},"end":{"row":143,"column":22},"action":"insert","lines":["-"],"id":595}],[{"start":{"row":143,"column":22},"end":{"row":143,"column":23},"action":"insert","lines":[" "],"id":596}],[{"start":{"row":147,"column":24},"end":{"row":147,"column":25},"action":"insert","lines":[" "],"id":597}],[{"start":{"row":147,"column":25},"end":{"row":147,"column":26},"action":"insert","lines":["-"],"id":598}],[{"start":{"row":147,"column":26},"end":{"row":147,"column":27},"action":"insert","lines":["-"],"id":599}],[{"start":{"row":147,"column":27},"end":{"row":147,"column":28},"action":"insert","lines":["?"],"id":600}],[{"start":{"row":147,"column":27},"end":{"row":147,"column":28},"action":"remove","lines":["?"],"id":601}],[{"start":{"row":147,"column":27},"end":{"row":147,"column":28},"action":"insert","lines":[">"],"id":602}],[{"start":{"row":143,"column":0},"end":{"row":144,"column":0},"action":"insert","lines":["              <div class=\"video-archive-element col-xs-3\">",""],"id":604}],[{"start":{"row":142,"column":14},"end":{"row":142,"column":15},"action":"insert","lines":["<"],"id":605}],[{"start":{"row":142,"column":15},"end":{"row":142,"column":16},"action":"insert","lines":["!"],"id":606}],[{"start":{"row":142,"column":16},"end":{"row":142,"column":17},"action":"insert","lines":["-"],"id":607}],[{"start":{"row":142,"column":17},"end":{"row":142,"column":18},"action":"insert","lines":["-"],"id":608}],[{"start":{"row":142,"column":62},"end":{"row":142,"column":63},"action":"insert","lines":["-"],"id":609}],[{"start":{"row":142,"column":63},"end":{"row":142,"column":64},"action":"insert","lines":["-"],"id":610}],[{"start":{"row":142,"column":64},"end":{"row":142,"column":65},"action":"insert","lines":[">"],"id":611}],[{"start":{"row":143,"column":48},"end":{"row":143,"column":56},"action":"remove","lines":["col-xs-3"],"id":612}],[{"start":{"row":143,"column":47},"end":{"row":143,"column":48},"action":"remove","lines":[" "],"id":613}],[{"start":{"row":148,"column":28},"end":{"row":165,"column":24},"action":"remove","lines":["","                  <div class=\"row video-title\">","                      <div class=\"col-xs-10\">","                      @if($video->alias == \"\")","                      <label class=\"video-label\">No Title</label>","                      <input class=\"form-control edit-title hidden\" type=\"text\" value=\"\" placeholder=\"Enter new title\"/>","                      @else","                      <label class=\"video-label\">{{$video->alias}}</label>","                      <input class=\"form-control edit-title hidden\" type=\"text\" value=\"{{$video->alias}}\" placeholder=\"Enter new title\"/>","                      @endif","                      </div>","                      <div class=\"col-xs-2\">","                      <a class=\"btn-edit-video-title\"><i class=\"material-icons\">mode_edit</i></a>","                      <a class=\"btn-save-video-title hidden\"><i class=\"material-icons\">done</i></a>","                      <a class=\"btn-delete-video\"><i class=\"material-icons\">delete_forever</i></a>","                      <input class=\"recorded_video_id\" type=\"hidden\" value=\"{{$video->id}}\"/>","                      </div>","                  </div>"],"id":614}],[{"start":{"row":151,"column":22},"end":{"row":168,"column":24},"action":"insert","lines":["","                  <div class=\"row video-title\">","                      <div class=\"col-xs-10\">","                      @if($video->alias == \"\")","                      <label class=\"video-label\">No Title</label>","                      <input class=\"form-control edit-title hidden\" type=\"text\" value=\"\" placeholder=\"Enter new title\"/>","                      @else","                      <label class=\"video-label\">{{$video->alias}}</label>","                      <input class=\"form-control edit-title hidden\" type=\"text\" value=\"{{$video->alias}}\" placeholder=\"Enter new title\"/>","                      @endif","                      </div>","                      <div class=\"col-xs-2\">","                      <a class=\"btn-edit-video-title\"><i class=\"material-icons\">mode_edit</i></a>","                      <a class=\"btn-save-video-title hidden\"><i class=\"material-icons\">done</i></a>","                      <a class=\"btn-delete-video\"><i class=\"material-icons\">delete_forever</i></a>","                      <input class=\"recorded_video_id\" type=\"hidden\" value=\"{{$video->id}}\"/>","                      </div>","                  </div>"],"id":615}],[{"start":{"row":175,"column":18},"end":{"row":175,"column":19},"action":"insert","lines":["<"],"id":616}],[{"start":{"row":175,"column":19},"end":{"row":175,"column":20},"action":"insert","lines":["!"],"id":617}],[{"start":{"row":175,"column":20},"end":{"row":175,"column":21},"action":"insert","lines":["_"],"id":618}],[{"start":{"row":175,"column":21},"end":{"row":175,"column":22},"action":"insert","lines":["-"],"id":619}],[{"start":{"row":175,"column":21},"end":{"row":175,"column":22},"action":"remove","lines":["-"],"id":620}],[{"start":{"row":175,"column":20},"end":{"row":175,"column":21},"action":"remove","lines":["_"],"id":621}],[{"start":{"row":175,"column":20},"end":{"row":175,"column":21},"action":"insert","lines":["-"],"id":622}],[{"start":{"row":175,"column":21},"end":{"row":175,"column":22},"action":"insert","lines":["-"],"id":623}],[{"start":{"row":180,"column":24},"end":{"row":180,"column":25},"action":"insert","lines":["-"],"id":624}],[{"start":{"row":180,"column":25},"end":{"row":180,"column":26},"action":"insert","lines":["-"],"id":625}],[{"start":{"row":180,"column":26},"end":{"row":180,"column":27},"action":"insert","lines":[">"],"id":626}],[{"start":{"row":181,"column":0},"end":{"row":187,"column":0},"action":"insert","lines":["                  <!--<div class=\"row video-archive-item-details\">","                      <div class=\"col-xs-6 recorded_on\">{{date('M d, Y H:i A', strtotime($video->created_at))}}</div>","                      <div class=\"col-xs-6 \">","                          <span class=\"recorded_by pull-right\">By: {{$video->recorded_by}}</span>","                          </div>","                  </div>-->",""],"id":627}],[{"start":{"row":181,"column":18},"end":{"row":181,"column":22},"action":"remove","lines":["<!--"],"id":628}],[{"start":{"row":186,"column":26},"end":{"row":186,"column":27},"action":"remove","lines":[">"],"id":629}],[{"start":{"row":186,"column":25},"end":{"row":186,"column":26},"action":"remove","lines":["-"],"id":630}],[{"start":{"row":186,"column":24},"end":{"row":186,"column":25},"action":"remove","lines":["-"],"id":631}],[{"start":{"row":181,"column":62},"end":{"row":182,"column":56},"action":"remove","lines":["","                      <div class=\"col-xs-6 recorded_on\">"],"id":632}],[{"start":{"row":181,"column":117},"end":{"row":183,"column":63},"action":"remove","lines":["</div>","                      <div class=\"col-xs-6 \">","                          <span class=\"recorded_by pull-right\">"],"id":633},{"start":{"row":181,"column":117},"end":{"row":181,"column":118},"action":"insert","lines":[" "]}],[{"start":{"row":181,"column":145},"end":{"row":183,"column":18},"action":"remove","lines":["</span>","                          </div>","                  "],"id":634}],[{"start":{"row":181,"column":117},"end":{"row":181,"column":118},"action":"insert","lines":[" "],"id":635}],[{"start":{"row":181,"column":118},"end":{"row":181,"column":126},"action":"insert","lines":["&middot;"],"id":636}],[{"start":{"row":170,"column":22},"end":{"row":170,"column":23},"action":"insert","lines":["!"],"id":637}],[{"start":{"row":170,"column":22},"end":{"row":170,"column":23},"action":"remove","lines":["!"],"id":638}],[{"start":{"row":170,"column":22},"end":{"row":170,"column":23},"action":"insert","lines":["<"],"id":639}],[{"start":{"row":170,"column":23},"end":{"row":170,"column":24},"action":"insert","lines":["!"],"id":640}],[{"start":{"row":170,"column":24},"end":{"row":170,"column":25},"action":"insert","lines":["-"],"id":641}],[{"start":{"row":170,"column":25},"end":{"row":170,"column":26},"action":"insert","lines":["-"],"id":642}],[{"start":{"row":170,"column":49},"end":{"row":170,"column":50},"action":"insert","lines":["-"],"id":643}],[{"start":{"row":170,"column":50},"end":{"row":170,"column":51},"action":"insert","lines":["-"],"id":644}],[{"start":{"row":170,"column":51},"end":{"row":170,"column":52},"action":"insert","lines":[">"],"id":645}],[{"start":{"row":173,"column":22},"end":{"row":173,"column":23},"action":"insert","lines":["<"],"id":646}],[{"start":{"row":173,"column":23},"end":{"row":173,"column":24},"action":"insert","lines":["!"],"id":647}],[{"start":{"row":173,"column":24},"end":{"row":173,"column":25},"action":"insert","lines":["_"],"id":648}],[{"start":{"row":173,"column":25},"end":{"row":173,"column":26},"action":"insert","lines":["-"],"id":649}],[{"start":{"row":173,"column":25},"end":{"row":173,"column":26},"action":"remove","lines":["-"],"id":650}],[{"start":{"row":173,"column":24},"end":{"row":173,"column":25},"action":"remove","lines":["_"],"id":651}],[{"start":{"row":173,"column":24},"end":{"row":173,"column":25},"action":"insert","lines":["-"],"id":652}],[{"start":{"row":173,"column":25},"end":{"row":173,"column":26},"action":"insert","lines":["-"],"id":653}],[{"start":{"row":173,"column":32},"end":{"row":173,"column":33},"action":"insert","lines":["-"],"id":654}],[{"start":{"row":173,"column":33},"end":{"row":173,"column":34},"action":"insert","lines":["-"],"id":655}],[{"start":{"row":173,"column":34},"end":{"row":173,"column":35},"action":"insert","lines":[">"],"id":656}],[{"start":{"row":153,"column":42},"end":{"row":153,"column":43},"action":"remove","lines":["0"],"id":657}],[{"start":{"row":153,"column":41},"end":{"row":153,"column":42},"action":"remove","lines":["1"],"id":658}],[{"start":{"row":153,"column":41},"end":{"row":153,"column":42},"action":"insert","lines":["8"],"id":659}],[{"start":{"row":162,"column":41},"end":{"row":162,"column":42},"action":"remove","lines":["2"],"id":660}],[{"start":{"row":162,"column":41},"end":{"row":162,"column":42},"action":"insert","lines":["4"],"id":661}],[{"start":{"row":162,"column":42},"end":{"row":162,"column":43},"action":"insert","lines":[" "],"id":662}],[{"start":{"row":162,"column":43},"end":{"row":162,"column":44},"action":"insert","lines":["t"],"id":663}],[{"start":{"row":162,"column":44},"end":{"row":162,"column":45},"action":"insert","lines":["e"],"id":664}],[{"start":{"row":162,"column":45},"end":{"row":162,"column":46},"action":"insert","lines":["x"],"id":665}],[{"start":{"row":162,"column":46},"end":{"row":162,"column":47},"action":"insert","lines":["t"],"id":666}],[{"start":{"row":162,"column":47},"end":{"row":162,"column":48},"action":"insert","lines":["-"],"id":667}],[{"start":{"row":162,"column":48},"end":{"row":162,"column":49},"action":"insert","lines":["r"],"id":668}],[{"start":{"row":162,"column":49},"end":{"row":162,"column":50},"action":"insert","lines":["i"],"id":669}],[{"start":{"row":162,"column":50},"end":{"row":162,"column":51},"action":"insert","lines":["g"],"id":670}],[{"start":{"row":162,"column":51},"end":{"row":162,"column":52},"action":"insert","lines":["h"],"id":671}],[{"start":{"row":162,"column":52},"end":{"row":162,"column":53},"action":"insert","lines":["t"],"id":672}],[{"start":{"row":148,"column":26},"end":{"row":148,"column":27},"action":"remove","lines":["-"],"id":673}],[{"start":{"row":148,"column":25},"end":{"row":148,"column":26},"action":"remove","lines":["-"],"id":674}],[{"start":{"row":148,"column":24},"end":{"row":148,"column":25},"action":"remove","lines":[" "],"id":675}],[{"start":{"row":148,"column":24},"end":{"row":148,"column":25},"action":"remove","lines":[">"],"id":676}],[{"start":{"row":144,"column":18},"end":{"row":144,"column":23},"action":"remove","lines":["<!-- "],"id":677}],[{"start":{"row":144,"column":18},"end":{"row":144,"column":23},"action":"insert","lines":["<!-- "],"id":678},{"start":{"row":148,"column":24},"end":{"row":148,"column":25},"action":"insert","lines":[">"]},{"start":{"row":148,"column":24},"end":{"row":148,"column":27},"action":"insert","lines":[" --"]}],[{"start":{"row":144,"column":17},"end":{"row":144,"column":22},"action":"remove","lines":[" <!--"],"id":679,"ignore":true},{"start":{"row":148,"column":24},"end":{"row":148,"column":28},"action":"remove","lines":[" -->"]}]]},"ace":{"folds":[{"start":{"row":18,"column":79},"end":{"row":74,"column":12},"placeholder":"..."}],"scrolltop":1315,"scrollleft":0,"selection":{"start":{"row":145,"column":68},"end":{"row":145,"column":68},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":452,"mode":"ace/mode/php"}},"timestamp":1495440621927,"hash":"60c02d59053e256cb36fca8e5368e8a6d47a1e72"}