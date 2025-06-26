@extends('admin::layouts.navigation')

@section('content')
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        Név
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Email
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Tárgy
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Üzenet
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Akciók
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emails as $email)
                    <tr class="bg-white border-b">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            {{ $email->name }}#
                        </th>
                        <td class="py-4 px-6">
                            {{ $email->email }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $email->subject }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $email->message }}
                        </td>
                        <td class="py-4 px-6 flex text-2xl">
                            <form action="{{ route('admin.email.delete', $email->id) }}" method="post" onsubmit="return confirm('Biztosan törölni szeretné ezt az emailt?');">
                                @method('DELETE')
                                @csrf
                                <button type="submit"><i class="ri-delete-bin-6-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection