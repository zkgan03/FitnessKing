using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace ASPNET_WebAPI.Migrations
{
    /// <inheritdoc />
    public partial class Update : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<string>(
                name: "CustomId",
                table: "TransactionDetails",
                type: "TEXT",
                nullable: false,
                defaultValue: "");

            migrationBuilder.AddColumn<string>(
                name: "ReferenceId",
                table: "TransactionDetails",
                type: "TEXT",
                nullable: false,
                defaultValue: "");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "CustomId",
                table: "TransactionDetails");

            migrationBuilder.DropColumn(
                name: "ReferenceId",
                table: "TransactionDetails");
        }
    }
}
