<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Iceberg Push Service</title>
</head>
<body>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center">Send Push</h1>
            <form action="{{ url('sendpushservice') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="phone">Phone number</label>
                            <input name="phone"
                                   id="phone"
                                   type="tel"
                                   class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                   value="{{ old('phone') }}">
                            @if($errors->has('phone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="token">Push token</label>
                            <input name="token"
                                   id="token"
                                   type="text"
                                   class="form-control {{ $errors->has('token') ? 'is-invalid' : '' }}"
                                   value="{{ old('token') }}">
                            @if($errors->has('token'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('token') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input name="title" id="title" type="text" class="form-control" value="{{ old('title') ?? 'Тайтл' }}">
                        </div>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <input name="body" id="body" type="text" class="form-control" value="{{ old('body') ?? 'Боди' }}">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message"
                                      id="message"
                                      class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                                      rows="5">{{ old('message') }}</textarea>
                            @if($errors->has('message'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('message') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </form>
            @if(session()->has('pushresult'))
                <div class="mt-5">
                    {!! var_dump(session()->get('pushresult')) !!}
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>
</body>
</html>
