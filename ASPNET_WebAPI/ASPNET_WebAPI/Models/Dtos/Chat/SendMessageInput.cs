namespace ASPNET_WebAPI.Models.Dtos.Chat;

public class SendMessageInput
{
    public string Text { get; set; } = "";
}

public class HistoryInput
{
    public List<HistoryItem> Messages { get; set; } = [];
    public class HistoryItem
    {
        public string Role { get; set; } = "User";
        public string Content { get; set; } = "";
    }
}