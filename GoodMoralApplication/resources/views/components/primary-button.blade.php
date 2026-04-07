<button {{ $attributes->merge(['type' => 'submit', 'style' => 'background: linear-gradient(135deg, rgba(0, 176, 80, 1) 0%, rgba(0, 150, 68, 1) 100%); border: none; color: #ffffff !important; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 176, 80, 0.3); font-size: 14px; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 2px solid transparent; text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px;']) }}
    onmouseover="this.style.background='linear-gradient(135deg, rgba(0, 150, 68, 1) 0%, rgba(0, 176, 80, 1) 100%)'; this.style.color='#ffffff'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(0, 176, 80, 0.4)'"
    onmouseout="this.style.background='linear-gradient(135deg, rgba(0, 176, 80, 1) 0%, rgba(0, 150, 68, 1) 100%)'; this.style.color='#ffffff'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 176, 80, 0.3)'"
    onfocus="this.style.outline='none'; this.style.color='#ffffff'; this.style.border='2px solid #fff'; this.style.boxShadow='0 0 0 3px rgba(0, 176, 80, 0.5)'"
    onblur="this.style.border='2px solid transparent'; this.style.color='#ffffff'; this.style.boxShadow='0 4px 15px rgba(0, 176, 80, 0.3)'"
    onclick="this.style.color='#ffffff'">
    <span style="color: #ffffff !important;">{{ $slot }}</span>
</button>
