<div class="modal fade texttospeachmodal" id="texttospeachmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ lang('Text To Speach') }}</h5>
            <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="texttospeachcontent" id="texttospeachcontent">
            <div class="form-group">
                <label for="lang-select" class="form-label">{{lang('Select Target Language')}}</label>
                <div class="custom-controls-stacked d-md-flex" >
                    <select class="form-control select2_texttospeach" data-placeholder="{{lang('Select Target Language')}}" id="lang-select" onload="loadVoices()">
                        @foreach (languages() as $code => $name)
                            <option value="{{ $code }}" {{ $code == 'en' ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button class="btn btn-success" onclick="speakText()"><i class="ri-play-circle-line"></i>Play</button>
            <button class="btn btn-warning" onclick="pause()"><i class="ri-pause-circle-line"></i>Pause</button>
            <button class="btn btn-info" onclick="resume()"><i class="ri-play-line"></i>Resume</button>
            <button class="btn btn-danger" onclick="stop()"><i class="ri-stop-circle-line"></i>Stop</button>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="stop()" data-bs-dismiss="modal">{{ lang('Close') }}</button>
        </div>
      </div>
    </div>
</div>
