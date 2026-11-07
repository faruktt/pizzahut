<div class="kot-wrap" style="color:#000000; font-family:'Courier New', Courier, monospace; line-height:1.3; text-align:left;">
    <!-- KOT Header -->
    <div style="text-align:center; border-bottom:2px dashed #000; padding-bottom:6px; margin-bottom:6px;">
        <div style="font-size:14px; font-weight:900; letter-spacing:0.5px; text-transform:uppercase;">
            {{ \App\Models\Setting::getValue('site_name', 'Italian Pizza Hut') }}
        </div>
        <div style="display:inline-block; background:#000; color:#fff; font-size:11px; font-weight:900; padding:3px 10px; border-radius:3px; margin:4px 0 2px 0; text-transform:uppercase; letter-spacing:1px;">
            *** KITCHEN ORDER TICKET (KOT) ***
        </div>
    </div>

    <!-- Order Meta -->
    <div style="font-size:10px; margin-bottom:6px;">
        <div style="display:flex; justify-content:space-between; font-weight:900; font-size:12px;">
            <span>#{{ $order->order_number }}</span>
            <span style="border:1.5px solid #000; padding:1px 6px; border-radius:3px; font-size:10px;">{{ strtoupper(str_replace('_', ' ', $order->order_type)) }}</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:2px; font-size:9.5px;">
            <span>Date: {{ $order->created_at->format('d M Y, h:i A') }}</span>
            <span>Server: {{ $order->admin->name ?? 'Admin' }}</span>
        </div>

        @if($order->order_type === 'dine_in' || $order->restaurant_table_id)
            <div style="margin-top:6px; border:2px solid #000; padding:4px; text-align:center; font-weight:900; font-size:14px; text-transform:uppercase; background:#f1f5f9;">
                🍽️ TABLE: {{ $order->table->table_number ?? 'Dine-In' }}
            </div>
        @endif

        @if($order->customer_name && !in_array(strtolower($order->customer_name), ['walk-in', 'dine-in']) && !\Illuminate\Support\Str::startsWith($order->customer_name, 'Dine-in ('))
            <div style="margin-top:4px; font-size:9.5px;">
                <strong>Customer:</strong> {{ $order->customer_name }}
                @if($order->customer_phone && $order->customer_phone !== 'Walk-in')
                    ({{ $order->customer_phone }})
                @endif
            </div>
        @endif
    </div>

    <!-- Items Section Header -->
    <div style="border-top:1.5px solid #000; border-bottom:1px solid #000; padding:3px 0; display:flex; justify-content:space-between; font-weight:900; font-size:10.5px; text-transform:uppercase;">
        <span style="width:25%;">QTY</span>
        <span style="width:75%;">ITEM / COOKING SPEC</span>
    </div>

    <!-- Items List -->
    <div style="margin-top:4px;">
        @foreach($order->items as $index => $item)
            @php
                $vars = [];
                if ($item->size_name && strtolower($item->size_name) !== 'standard') $vars[] = $item->size_name;
                if ($item->crust_name && strtolower($item->crust_name) !== 'standard') $vars[] = $item->crust_name;
            @endphp
            <div style="padding:5px 0; border-bottom:1px dashed #000;">
                <div style="display:flex; align-items:flex-start;">
                    <span style="width:25%; font-size:14px; font-weight:900; color:#000;">
                        [{{ $item->quantity }}x]
                    </span>
                    <div style="width:75%;">
                        <div style="font-size:12px; font-weight:900; color:#000; line-height:1.2;">
                            {{ $item->product_name }}
                        </div>
                        @if(count($vars) > 0)
                            <div style="font-size:10px; font-weight:800; color:#000; margin-top:2px;">
                                👉 {{ implode(' • ', $vars) }}
                            </div>
                        @endif
                        @if($item->addons && $item->addons->isNotEmpty())
                            <div style="font-size:9.5px; font-weight:700; color:#000; margin-top:2px;">
                                @foreach($item->addons as $addon)
                                    <div>+ {{ $addon->add_on_name }}</div>
                                @endforeach
                            </div>
                        @endif
                        @if($item->combo_details)
                            <div style="font-size:9px; font-weight:600; color:#000; margin-top:2px; font-style:italic;">
                                Combo: {{ $item->combo_details }}
                            </div>
                        @endif
                        @if($item->special_instructions)
                            <div style="font-size:9.5px; font-weight:800; color:#000; margin-top:3px; background:#fef08a; padding:2px 4px; border-left:2.5px solid #000;">
                                ⚠️ NOTE: {{ $item->special_instructions }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Special Order Instructions -->
    @if($order->special_instructions)
        <div style="margin-top:6px; border:1.5px solid #000; padding:4px 6px; background:#fffbe8;">
            <div style="font-weight:900; font-size:9.5px; text-transform:uppercase;">⚠️ ORDER KITCHEN NOTE:</div>
            <div style="font-size:10.5px; font-weight:800; margin-top:2px;">{{ $order->special_instructions }}</div>
        </div>
    @endif

    <!-- Summary -->
    <div style="border-top:1.5px solid #000; margin-top:6px; padding-top:4px; display:flex; justify-content:space-between; font-size:10px; font-weight:900;">
        <span>ITEMS: {{ $order->items->count() }}</span>
        <span>TOTAL QTY: {{ $order->items->sum('quantity') }}</span>
    </div>

    <!-- Footer -->
    <div style="border-top:1px dashed #000; margin-top:6px; padding-top:4px; text-align:center; font-size:9px; font-weight:800;">
        <div>*** SEND TO KITCHEN / CHEF COPY ***</div>
        <div style="font-size:8px; margin-top:2px; color:#475569;">Printed: {{ date('d M Y, h:i A') }}</div>
    </div>
</div>
