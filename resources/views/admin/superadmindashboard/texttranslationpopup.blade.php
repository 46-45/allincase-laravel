<div class="modal fade texttranslationmodal" id="texttranslationmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ lang('Text Translation') }}</h5>
            <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="translate-lang" class="form-label">{{lang('Select Target Language')}}</label>
                <div class="custom-controls-stacked d-md-flex" >
                    <select class="form-control select2_texttranslation" data-placeholder="{{lang('Select Target Language')}}" id="translate-lang" onload="loadVoices()">
                        @foreach (languages() as $code => $name)
                            @if($code != 'lu')
                                <option value="{{ $code }}" {{ $code == 'en' ? 'selected' : '' }}>{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ lang('Close') }}</button>
            <button class="btn btn-secondary" id="translateButtonSave">{{lang('Translate')}}</button>
        </div>
      </div>
    </div>
</div>
